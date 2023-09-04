<script lang="ts">
import { useMeta } from 'vue-meta'
import Nav from "../components/Nav.vue";
import Modal from "../components/Modal.vue";
import Decorator from "../components/Decorator.vue";
import CardsGenerated from "../components/CardsGenerated.vue";
import { defineComponent } from 'vue';
import InfoBox from "../components/InfoBox.vue";
import { useCardStore } from '../stores/useCardStore';
import { useUserStore } from "../stores/useUserStore.ts";

export default defineComponent({
    data() {
        return {
            cardIsAvailable: true, // /reward/status
            showReveal: false, // always set to false when component is mounted
            cardStore: useCardStore(),
            cards: [],
            userStore: useUserStore(),
            userAccountActiv: false
        }
    },
    setup() {
        useMeta({
            title: 'Récompense',
            description: "La page récompense de l'application Historiska permet à chaque joueur de récupérer tous les jours son lot de cartes.",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    mounted() {
        this.cardStore.fetchRewardStatus().then(status => {
            this.cardIsAvailable = status.is_available;
        })

        this.userAccountActiv = this.userStore.getAuthUser.is_verified;
    },
    methods: {
        generateCards() {
            this.showReveal = true;
            this.cardIsAvailable = false;

            this.cardStore.fetchReward().then(cards => {
                this.cards = cards;
            });

            this.cardStore.clearStore();
        }
    },
    components: {
        InfoBox,
        Nav,
        Decorator,
        CardsGenerated,
        Modal,
    }
});
</script>

<template>
    <div>
        <Nav></Nav>
        <section class="reward-section container">
            <Modal></Modal>
            <div v-if="showReveal" class="content-container">
                <CardsGenerated :cards="cards"></CardsGenerated>
            </div>
            <div v-else>
                <div class="info-box">
                    <InfoBox title="Informations"
                        text="Seulement 3 cartes peuvent être générées par jour. Revenez tous les jours pour en générer de nouvelles. <br> La génération ne se reporte pas de jour en jour. Les nouvelles générations sont disponibles à partir de minuit." />
                </div>
                <Decorator element="<h1>Récompense</h1>" class="title"></Decorator>
                <div v-if="!userAccountActiv" class="content-container">
                    <p>Activer votre compte pour avoir accès à cette fonctionnalité !</p>
                </div>
                <div v-else>
                    <div v-if="cardIsAvailable" class="content-container">
                        <p>Générer vos cartes quotidiennes</p>
                        <button class="btn" @click="generateCards">Générer</button>
                    </div>
                    <div v-else class="content-container">
                        <p>Vous avez déjà généré vos cartes quotidiennes!</p>
                        <p>Revenez demain pour récupérer de nouvelles récompenses.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template> 

<style scoped lang="scss">
.content-container {
    display: flex;
    flex-direction: column;
    align-items: center;

    p {
        text-align: center;
    }
}

.reward-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 90vh;

    .title {
        margin-top: 70px;
        margin-bottom: 25px;
    }

    .btn {
        margin-top: 40px;
    }
}

</style>