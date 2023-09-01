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
	<router-view v-slot="{ Component }">
		<transition name="fade" mode="out-in">
			<component :is="Component" />
		</transition>
	</router-view>
</template>

<style lang="scss">
@import './styles/bases.scss'; // import bases and apply to all component
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.6s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
