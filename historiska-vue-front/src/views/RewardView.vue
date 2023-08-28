<script lang="ts">
import { useMeta } from 'vue-meta'
import Nav from "../components/Nav.vue";
import Modal from "../components/Modal.vue";
import Decorator from "../components/Decorator.vue";
import CardsGenerated from "../components/CardsGenerated.vue";
import { defineComponent } from 'vue';

export default defineComponent({
    data() {
        return {
            cardIsAvailable: true, // /reward/status
            showReveal: false, // always set to false when component is mounted
        }
    },
    setup() {
        useMeta({
            title: 'Récompense',
            description: "La page récompense de l'application Historiska permet à chaque joueur de récupérer tous les jours son lot de cartes.",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    methods: {
        generateCards() {
            this.showReveal = true;
            this.cardIsAvailable = false;
            // TODO call api to generate cards
        }
    },
    components: {
        Nav,
        Decorator,
        CardsGenerated,
        Modal,
    }
});
</script>

<template>
    <Nav></Nav>
    <section class="reward-section">
        <Modal></Modal>
        <div v-if="showReveal" class="content-container">
            <CardsGenerated :cards="[{
                name: 'Platon',
                quantity: 3,
                description: 'ceci est une description',
                code: '007',
                birth: -400,
                death: -320,
                image_path: './platon.png',
                category: {
                    name: 'Philosophe'
                },
                country: {
                    name: 'Grèce'
                }
            }, {
                name: 'Jean christophe',
                quantity: 3,
                description: 'ceci est une description',
                code: '007',
                birth: -400,
                death: -320,
                image_path: './platon.png',
                category: {
                    name: 'Philosophe'
                },
                country: {
                    name: 'Grèce'
                }
            }, {
                name: 'Platon',
                quantity: 1,
                description: 'ceci est une description',
                code: '007',
                birth: -400,
                death: -320,
                image_path: './platon.png',
                category: {
                    name: 'Philosophe'
                },
                country: {
                    name: 'Grèce'
                }
            }]"></CardsGenerated>
        </div>
        <div v-else>
            <Decorator element="<h1>Récompense</h1>" class="title"></Decorator>
            <div v-if="cardIsAvailable" class="content-container">
                <p>Générer vos cartes quotidiennes</p>
                <button class="btn" @click="generateCards">Générer</button>
            </div>
            <div v-else class="content-container">
                <p>Vous avez déjà généré vos cartes quotidiennes!</p>
                <p>Revenez dans X heures pour récupérer de nouvelles cartes.</p>
            </div>
        </div>
    </section>
</template> 

<style scoped lang="scss">
.content-container
{
    display: flex;
    flex-direction: column;
    align-items: center;
}
.reward-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 70px;
    height: 90vh;
    .title
    {
        margin-bottom: 25px;
    }

    .btn {
        margin-top: 40px;
    }
}
</style>