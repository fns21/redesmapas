app.component('entity-card', {
    template: $TEMPLATES['entity-card'],

    setup() { 
        // os textos estão localizados no arquivo texts.php deste componente 
        const text = Utils.getTexts('entity-card')
        return { text }
    },

    data() {
        return {}
    },

    props: {
        entity: {
            type: Entity,
            required: true
        }
    },

    data() {
        return {
        }
    },

    computed: {
        seals() {
            return (this.entity.seals.length > 0 ? this.entity.seals.slice(0, 2) : false);
        },
        areas() {
            return (Array.isArray(this.entity.terms.area) ? this.entity.terms.area.join(", ") : false);
        },
        tags() {
            return (Array.isArray(this.entity.terms.tag) ? this.entity.terms.tag.join(", ") : false);
        },
        linguagens() {
            return (Array.isArray(this.entity.terms.linguagem) ? this.entity.terms.linguagem.join(", ") : false);
        }
    },
    
    methods: {
    },
});
