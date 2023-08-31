<script lang="ts">
import { RouterView } from 'vue-router'
</script>

<template>
	<metainfo>
		<meta name="msapplication-TileColor" content="#3D314A">
		<meta name="theme-color" content="#ffffff">
		<template v-slot:title="{ content }">
			{{
				content ? `${content} | Historiska` : `Historiska`
			}}
		</template>
	</metainfo>
	<RouterView />
</template>

<style lang="scss">
@import './styles/bases.scss'; // import bases and apply to all component
</style>
