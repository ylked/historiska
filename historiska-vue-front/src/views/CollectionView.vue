<script lang="ts">
import { useMeta } from 'vue-meta'
import Nav from '../components/Nav.vue'
import Banner from '../components/Banner.vue'
import Modal from '../components/Modal.vue'
import CategoryDropdown from '../components/CategoryDropdown.vue'
import { useCardStore } from '../stores/useCardStore'
import type { Category } from '../models/Category.vue'

export default {
    setup() {
        useMeta({
            title: 'Collection',
            description: "La page collection est déstinée à visualiser la collection de carte que le joueur actuellement connecté possède",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    data() {
        return {
            showUnownedCards: false,
            collapsedAll: false,
            onlyGold: false,
            categories: <Category[]>[],
        }
    },
    mounted() {
        const cardStore = useCardStore();
        cardStore.fetchCategories().then(categories => {
            this.categories = categories;
        }).catch(error => {
            // Gérer les erreurs ici
            console.error("Error:", error);
        });
    },
    components: {
        Nav,
        Banner,
        CategoryDropdown,
        Modal
    }
}
</script>

<template>
    <div>
        <Nav></Nav>
        <Banner title="Collection"></Banner>
        <section class="checkbox-section">
            <label for="show-not-owned-card" class="checkbox-container">
                <input type="checkbox" id="show-not-owned-card" v-model="showUnownedCards">
                <span class="checkmark"></span>
                Afficher les cartes non possédées
            </label>
            <label for="collapsed-all" class="checkbox-container">
                <input type="checkbox" id="collapsed-all" v-model="collapsedAll">
                <span class="checkmark"></span>
                Tout ouvrir
            </label>
            <label for="gold-only" class="checkbox-container">
                <input type="checkbox" id="gold-only" v-model="onlyGold">
                <span class="checkmark"></span>
                Cartes dorées
            </label>
        </section>
        <section class="collection-container">
            <Modal></Modal>
            <div class="container">
                <CategoryDropdown v-for="category in categories" :collapsed="collapsedAll" :only_gold="onlyGold"
                    :show_unowned_cards="showUnownedCards" :id="category.id" :name="category.name"
                    :owned_quantity="category.owned_qty" :total_quantity="category.total_qty">
                </CategoryDropdown>
            </div>
        </section>
    </div>
</template> 

<style scoped lang="scss">
.collection-container {
    section {
        margin-top: 30px;

        &:first-child {
            margin-top: 0px;
        }
    }

    margin-bottom: 30px;
}

.checkbox-section {
    padding: 30px 0;
    display: flex;
    justify-content: center;
}

.checkbox-container {
    display: block;
    position: relative;
    padding-left: 30px;
    margin-right: 30px;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;

    &:hover input~.checkmark {
        background-color: #ccc;
    }

    input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;

        &:checked~.checkmark {
            &:after {
                display: block;
            }
        }
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        border-radius: 3px;
        background-color: #eee;

        &:after {
            content: "";
            position: absolute;
            display: none;
            width: 12px;
            height: 12px;
            top: 4px;
            left: 4px;
            border-radius: 3px;
            background-color: $purple;
        }
    }
}
</style>