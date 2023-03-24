app.component('registration-actions', {
    template: $TEMPLATES['registration-actions'],

    props: {
        registration: {
            type: Entity,
            required: true
        },
    },

    mounted() {
        window.addEventListener("message", (event) => {
            if (event.data.type == 'registration.update') {
                for (let key in event.data.data) {
                    this.registration[key] = event.data.data[key];
                }
            }
        });
    },

    data() {
        return {
            fields: $MAPAS.registrationFields,
        }
    },
    
    methods: {
        fieldName(field) {
            if (field == 'agent_instituicao') {
                return 'Instituição responsável'; 
            }

            if (field == 'agent_coletivo') {
                return 'Agente coletivo';
            }

            if (field.slice(0, 6) == 'field_') {
                for (let regField of this.fields) {
                    if (regField.fieldName == field) {
                        return regField.title;
                    }
                }
            }

            return 'Campo não identificado';

        },
        async send() {
            const data = {id: this.registration.id};
            if (this.registration.category) {
                data.category = this.registration.category;
            }
            
            try {
                this.registration.disableMessages();
                await this.save();
                this.registration.enableMessages();
                await this.registration.POST('send', {data});
                document.location.reload();
            } catch(error) {
                console.log(error);
            }
        },
        async save() {
            const iframe = document.getElementById('registration-form');
            const registration = this.registration;
            if (iframe) {
                registration.disableMessages();
                const promise = new Promise((resolve, reject) => {
                    Promise.all([
                        /* registration.save(false), */
                        new Promise((resolve, reject) => {
                            const saved = function(event) {    
                                if (event.data.type == "registration.saved") {
                                    if (event.data.error) {
                                        registration.__validationErrors = event.data.data;
                                    } else {
                                        registration.__validationErrors = {};
                                    }
                                    resolve(registration);
                                    window.removeEventListener("message", saved);
                                }
                            };
                            window.addEventListener("message", saved)
                        })
                    ]).then((values) => {
                        registration.enableMessages();
                        resolve(values[0]);
                    });

                });

                iframe.contentWindow.postMessage('registration.save');
                return promise;

            } else {
                return registration.save(false);
            }
        },
        exit() {
            this.registration.save().then(() => {
                if (window.history.length > 2) {
                    window.history.back();
                } else {
                    window.location.href = $MAPAS.baseURL+'panel';
                }
            });
        },
    },
});
