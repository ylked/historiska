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
            showUnownedCards: true,
        }
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
            <CategoryDropdown name="Philosophes" :owned_quantity="5" :total_quantity="10" :cards="[{
                name: 'Platon',
                quantity: 3,
                description: 'ceci est une description',
                code: '007',
                birth: -400,
                death: -320,
                image_path: './platon.png',
                is_golden: true,
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
            }, {
                name: 'Platon',
                quantity: 1,
                description: 'ceci est une description',
                code: '007',
                birth: -400,
                death: -320,
                image_path: './platon.png',
                is_golden: true,
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
            }, {
                name: 'Platon',
                quantity: 15,
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
                quantity: 5,
                description: 'ceci est une description',
                code: '007',
                birth: -400,
                death: -320,
                is_golden: true,
                image_path: './platon.png',
                category: {
                    name: 'Philosophe'
                },
                country: {
                    name: 'Grèce'
                }
            }, {
                name: 'Platon',
                quantity: 0,
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
                quantity: 0,
                is_golden: true,
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
            },]">
            </CategoryDropdown>
            <CategoryDropdown name="Politiques" :owned_quantity="5" :total_quantity="10" :cards="[{
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
            }, {
                name: 'Platon',
                quantity: 15,
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
                quantity: 5,
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
            },]">
            </CategoryDropdown>
        </div>
    </section>
</template> 

<style scoped lang="scss">
.collection-container
{
    section
    {
        margin-top: 30px;
        &:first-child
        {
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
            width: 14px;
            height: 14px;
            top: 3px;
            left: 3px;
            border-radius: 3px;
            background-color: $purple;
        }
    }
}
</style>