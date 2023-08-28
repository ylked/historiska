import { computed } from 'vue';
import { useMouseInElement } from '@vueuse/core';

export function useCardTransform(target: any) {
    const { elementX, elementY, isOutside, elementHeight, elementWidth } =
        useMouseInElement(target);

    const cardTransform = computed(() => {
        const MAX_ROTATION = 6;

        const rX = (
            MAX_ROTATION / 2 -
            (elementY.value / elementHeight.value) * MAX_ROTATION
        ).toFixed(2); // handles x-axis

        const rY = (
            (elementX.value / elementWidth.value) * MAX_ROTATION -
            MAX_ROTATION / 2
        ).toFixed(2); // handles y-axis

        return isOutside.value
            ? ''
            : `perspective(${elementWidth.value}px) rotateX(${rX}deg) rotateY(${rY}deg)`;
    });

    return { cardTransform };
}