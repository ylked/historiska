<script lang="ts">
import { useMeta } from 'vue-meta'
import Nav from '../components/Nav.vue'
import Banner from '../components/Banner.vue'
import Modal from '../components/Modal.vue'
import CategoryDropdown from '../components/CategoryDropdown.vue'

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
            cards: [
                {
                    id: 1,
                    name: 'Platon',
                    quantity: 3,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: true,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 2,
                    name: 'Jean christophe',
                    quantity: 3,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: false,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 3,
                    name: 'Platon',
                    quantity: 1,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: false,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 4,
                    name: 'Platon',
                    quantity: 1,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: true,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 5,
                    name: 'Platon',
                    quantity: 1,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: false,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 6,
                    name: 'Platon',
                    quantity: 15,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: false,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 7,
                    name: 'Platon',
                    quantity: 5,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    is_gold: true,
                    image_path: './platon.png',
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 8,
                    name: 'Platon',
                    quantity: 0,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: false,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }, {
                    id: 9,
                    name: 'Platon',
                    quantity: 0,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_gold: true,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }
            ]
        }
    },
    mounted() {
        //init stores
        console.log("MON LOG DANS TOUTES CES MERDES");
        console.log(this.$store);
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
    <Nav></Nav>
    <Banner title="Collection"></Banner>
    <section class="checkbox-section">
        <label for="show-not-owned-card" class="checkbox-container">
            <input type="checkbox" id="show-not-owned-card" v-model="showUnownedCards">
            <span class="checkmark"></span>
            Afficher les cartes non possédées
        </label>
    </section>
    <section class="collection-container">
        <Modal></Modal>
        <div class="container">
            <CategoryDropdown :show_unowned_cards="showUnownedCards" name="Philosophes" :owned_quantity="5" :total_quantity="10" :cards="cards">
            </CategoryDropdown>
            <CategoryDropdown :show_unowned_cards="showUnownedCards" name="Politiques" :owned_quantity="5" :total_quantity="10" :cards="cards">
            </CategoryDropdown>
        </div>
    </section>
</template> 

<style scoped lang="scss">
.collection-container {
    section {
        margin-top: 30px;

        &:first-child {
            margin-top: 0px;
        }
    }
}

.checkbox-section {
    padding: 30px 0;
    display: flex;
    justify-content: center;
}

.checkbox-container {
    display: block;
    position: relative;
    padding-left: 35px;
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