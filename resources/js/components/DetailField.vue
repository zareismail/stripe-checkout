<template>
    <panel-item :field="field">
    	<span slot="value">
    		<index-stripe-checkout v-bind="$attrs"/>
    	</span>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
