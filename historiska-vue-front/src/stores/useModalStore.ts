import { defineStore } from "pinia";

import { extend } from "@vue/shared";

const component = extend({});
type VueComponent = InstanceType<typeof component>;

export interface IModalProps {
    component: null | VueComponent;
    props?: object;
}

export interface IModalState {
    modalState: IModalProps;
}

const basicState = { component: null, props: {} };

export default defineStore("modal-store", {
    state: (): IModalState => ({ modalState: basicState }),
    actions: {
        openModal(payload: IModalProps) {
            const { props, component } = payload;
            this.modalState = { component, props: props || {} };
        },
        closeModal() {
            this.modalState = basicState;
        },
    },
    getters: {},
});