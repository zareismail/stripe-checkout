<template> 
	<div>
		<button @click="loadStripe" class="btn btn-primary btn-default cursor-pointer text-xs">{{ 
			__('Pay') 
		}}</button>
		<modal v-if="loading"> 
			<div 
	      class="bg-40 rounded-lg shadow-lg overflow-hidden p-8 flex flex-col justify-center"
	      style="width: 500px"
      >
      	<form ref="stripeForm" @submit.prevent.stop="processPayment" class="flex flex-col">
      		<span ref="stripeCard"></span>
      		<div class="flex mt-8 flex-col">
            <div class="flex">
              <h4 class="py-4 px-1 text-sm inline w-1/4">{{ 
                __('Amount') 
                }}<span class="text-xs text-success"> ({{ 
                  field.currency 
              }}):</span></h4>

              <input :readonly="! field.custom" class="p-3 m-1 rounded w-3/4" type="number" name="amount" v-model="amount">
            </div> 
            <div class="flex mt-8 justify-center">
  						<button 
  							:type="submit" 
  							:disabled="processing" 
  							class="btn btn-default bg-success text-white mx-1"
  						>{{ __('Pay') }}<loading :loading="processing"></loading></button>
  						<button class="btn btn-default btn-danger" type="button" @click="handleClose">{{ __('Cancel') }}</button>  
            </div>
      		</div>
      	</form> 
			</div>
		</modal> 
	</div>
</template>

<script>
import {loadStripe} from '@stripe/stripe-js';

export default {
  props: ['resourceName', 'field'],

  data: () => ({
  	loading: false,
  	processing: false,
    amount: 0.00,
  	stripe: null,
  	card: null,
  }), 

  mounted() {
  	this.processing = false
  	this.loading = false
    this.amount = this.field.amount
  },

  methods: {  
  	async loadStripe() {  
  		this.loading = true

  		this.stripe = await loadStripe(this.field.key); 
	  	// Create an instance of the card Element.
			this.card = this.stripe.elements().create('card');

			// Add an instance of the card Element into the `card-element` <div>.
			this.card.mount(this.$refs.stripeCard);

			this.card.on('change', function(event) { 
			  if (event.error) {
			    Nova.error(event.error.message);
			  }  
			});  
  	},
		// Submit the form with the token ID.
		async processPayment(event) { 
		  await this.stripe.createToken(this.card).then(result => {
		    if (result.error) {
			    Nova.error(result.error.message);
		    } else {   
		    	this.checkout(result.token)
		    }
		  });  
  	},

  	async checkout(token) {
			this.processing = true
  		await this.sessionRequest(token)
  			.then(({ data }) => { 
	          return data
        })
        .then(session => {
        	return this.stripe.redirectToCheckout(session)
        })
        .then(result => { 
          if (result.error) {
            Nova.error(result.error.message);
          } 
        })
        .catch(error => { 
          Nova.error(error);
        });
			this.processing = false
  	},

  	sessionRequest(token) { 
  		return Nova.request().post(this.sessionUrl, this.queryPararms(token)) 
  	}, 

    queryPararms(token){ 
      let params = this.field.params ? this.field.params : {}

      return Object.assign(params, {
        token: token,
        amount: this.amount,
        currency: this.field.currency,
      }) 
    },

    /**
     * Close the modal.
     */
    handleClose() { 
    	this.loading = false
      this.$emit('close')
    },
	},

	computed: {
		sessionUrl: function() {
			return this.field.sessionUrl;
		}, 
	}
}
</script>
<style>
.StripeElement {
  box-sizing: border-box;

  height: 40px;

  padding: 10px 12px;

  border: 1px solid transparent;
  border-radius: 4px;
  background-color: white;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>
