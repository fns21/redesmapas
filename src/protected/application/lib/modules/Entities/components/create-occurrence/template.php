<?php 
use MapasCulturais\i;
$this->import('
    modal 
    select-entity
    mc-link
    ');
?>

<modal title="Inserir ocorrência no evento" classes="create-occurrence" :updateDescription="updateDescription">
    <template #default>
        <div class="grid-12">
            <div :class="['col-12', 'create-occurrence__section', {'active' : step==0}]">
                <label v-if="!space" class="create-occurrence__section--title"> <?= i::_e('Vincular um espaço para o evento') ?> </label>
                <label v-if="space" class="create-occurrence__section--title"> <?= i::_e('Espaço vinculado:') ?> </label>

                <div v-if="!space" class="create-occurrence__section--link-space">
                    <!-- Seletor de entidades - espaços -->
                    <select-entity type="space" openside="down-right" @select="selectSpace($event)"> <!-- @select="addAgent(groupName, $event)" :query="queries[groupName]" -->
                        <template #button="{ toggle }">
                            <button class="button button--icon button--text-outline" @click="toggle()"> <mc-icon name="add"></mc-icon> <?= i::_e('Adicionar') ?> </button>
                        </template>
                    </select-entity>                       

                    <?= i::_e('ou') ?>

                    <!-- create space -->
                    <button class="button button--icon button--primary-outline"> <mc-icon name="add"></mc-icon> <?= i::_e('Crie um novo espaço') ?> </button>
                </div>

                <div v-else class="create-occurrence__section--link-space space-info">
                    <div class="space-info__space">
                        <div class="space-info__space--title">
                            <mc-icon name="pin"></mc-icon> {{space.name}}
                            <a class="remove" @click="removeSpace()"> <mc-icon name="trash"></mc-icon></a>
                        </div>
                        <div v-if="space.endereco" class="space-info__space--address">
                            {{space.endereco}}
                        </div>
                        <div class="space-info__space--address">
                            Sem endereço
                        </div>

                    </div>
                    <div class="space-info__new">
                        <select-entity type="space" openside="down-right" @select="selectSpace($event)">
                            <template #button="{ toggle }">
                                <button class="button button--icon button--primary-outline" @click="toggle()"> <mc-icon name="add"></mc-icon> <?= i::_e('Selecionar outro espaço') ?> </button>
                            </template>
                        </select-entity>  
                    </div>
                </div>
            </div>

            <div :class="['col-6', 'sm:col-12', 'create-occurrence__section', {'active' : step==1}]">
                <span class="create-occurrence__section--title"> <?= i::_e('Qual a frequência do evento?') ?> </span>

                <div class="create-occurrence__section--fields">
                    <label class="create-occurrence__section--fields-field"> <input v-model="frequency" type="radio" name="frequency" value="once" /> <?= i::_e('uma vez') ?> </label>
                    <label class="create-occurrence__section--fields-field"> <input v-model="frequency" type="radio" name="frequency" value="weekly" /> <?= i::_e('semanal') ?> </label>
                    <label class="create-occurrence__section--fields-field"> <input v-model="frequency" type="radio" name="frequency" value="daily" /> <?= i::_e('todos os dias') ?> </label>
                </div>
            </div>

            <div v-if="frequency=='weekly'" :class="['col-12', 'create-occurrence__section', {'active' : step==1}]">
                <span class="create-occurrence__section--title"> <?= i::_e('Que dias da semana o evento se repete?') ?> </span>

                <div class="create-occurrence__section--fields">
                    <label class="create-occurrence__section--fields-field"><input v-model="day[0]" type="checkbox" name="day[0]"> <?= i::_e('Domingo') ?> </label>
                    <label class="create-occurrence__section--fields-field"><input v-model="day[1]" type="checkbox" name="day[1]"> <?= i::_e('Segunda') ?> </label>
                    <label class="create-occurrence__section--fields-field"><input v-model="day[2]" type="checkbox" name="day[2]"> <?= i::_e('Terça') ?> </label>
                    <label class="create-occurrence__section--fields-field"><input v-model="day[3]" type="checkbox" name="day[3]"> <?= i::_e('Quarta') ?> </label>
                    <label class="create-occurrence__section--fields-field"><input v-model="day[4]" type="checkbox" name="day[4]"> <?= i::_e('Quinta') ?> </label>
                    <label class="create-occurrence__section--fields-field"><input v-model="day[5]" type="checkbox" name="day[5]"> <?= i::_e('Sexta') ?> </label>
                    <label class="create-occurrence__section--fields-field"><input v-model="day[6]" type="checkbox" name="day[6]"> <?= i::_e('Sabado') ?> </label>
                </div>
            </div>

            <div :class="['col-12', 'create-occurrence__section', {'active' : step==2}]">
                <span class="create-occurrence__section--title"> <?= i::_e('Quando o evento ocorrerá?') ?> </span>

                <div class="grid-12">

                    <div v-if="frequency=='once'" class="col-6 sm:col-12">
                        <div class="create-occurrence__section--field">
                            <span class="label"><?= i::_e('Data inicial:') ?></span>
                            
                            <datepicker 
                                :locale="locale" 
                                :weekStart="0"
                                v-model="startsOn" 
                                :enableTimePicker='false' 
                                :dayNames="['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab']"
                                multiCalendarsSolo autoApply utc></datepicker>
                        </div>
                    </div>

                    <div v-else class="col-6 sm:col-12">
                        <div class="create-occurrence__section--field">
                            <span class="label"><?= i::_e('Data inicial - Data final:') ?></span>
                            
                            <datepicker 
                                :locale="locale" 
                                :weekStart="0"
                                v-model="dateRange" 
                                :enableTimePicker='false' 
                                :dayNames="['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab']"
                                range multiCalendars multiCalendarsSolo autoApply utc></datepicker>
                        </div>
                    </div>

                </div>     
            </div>

            <div :class="['col-12', 'create-occurrence__section', {'active' : step==3}]">
                <span class="create-occurrence__section--title"> <?= i::_e('Qual o horário do evento?') ?> </span>

                <div class="grid-12">
                    <div class="col-6 sm:col-12">                        
                        <div class="create-occurrence__section--field">
                            <span class="label"><?= i::_e('Horário inicial:') ?></span>
                            <datepicker v-model="startsAt" timePicker autoApply></datepicker>

                            <!-- <input v-model="startsAt" type="time" /> -->
                        </div>
                    </div>
                    <div class="col-6 sm:col-12">
                        <div class="create-occurrence__section--field">
                            <span class="label"><?= i::_e('Horário final:') ?></span>
                            <datepicker v-model="endsAt" timePicker autoApply></datepicker>
                            
                            <!-- <input v-model="endsAt" type="time" /> -->
                        </div>
                    </div>
                </div>  
            </div>

            <div :class="['col-12', 'create-occurrence__section', {'active' : step==4}]">
                <span class="create-occurrence__section--title"> <?= i::_e('Como será a entrada?') ?> </span>
                
                <div class="create-occurrence__section--fields">
                    <div class="grid-12">
                        <div class="col-12">
                            <div class="create-occurrence__section--fields">
                                <span class="label"> <?= i::_e('O evento será gratuito?') ?> </span>
                                <label class="create-occurrence__section--fields-field"> <input v-model="free" type="radio" name="free" :value="true" /> <?= i::_e('Sim') ?> </label>
                                <label class="create-occurrence__section--fields-field"> <input v-model="free" type="radio" name="free" :value="false" /> <?= i::_e('Não') ?> </label>
                            </div>
                        </div>
                        <div class="col-6 sm:col-12" v-if="!free">
                            <div class="create-occurrence__section--field">
                                <span class="label"><?= i::_e('Valor da entrada:') ?></span>
                                <input v-model="price" type="text" @input="mascaraMoeda($event);" />
                            </div>
                        </div>
                        <div class="col-6 sm:col-12">
                            <div class="create-occurrence__section--field">
                                <span class="label"><?= i::_e('Informações adicionais sobre a entrada:') ?></span>
                                <input type="text" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="['col-12', 'create-occurrence__section', {'active' : step==5}]">
                <span class="create-occurrence__section--title"> <?= i::_e('Resumo das informações: ') ?> </span>

                <div class="create-occurrence__section--fields">
                    <div class="grid-12">
                        <div class="col-12">
                            <div class="create-occurrence__section--field">
                                <span class="label"><?= i::_e('Descrição legível de data e horário') ?></span>
                                <div class="auto-description">
                                    <span>{{autoDescription}}</span>
                                    <button class="button button--icon button--sm"> <mc-icon name="copy"></mc-icon> copiar </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="create-occurrence__section--field">
                                <span class="label"><small><?= i::_e('Você pode usar a descrição gerada pelo sistema OU criar uma descrição customizada prenchendo o campo abaixo') ?></small></span>
                                <input v-model="description" type="text" name="description" placeholder="<?= i::_e('Preencha aqui o resumo customizado') ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </template>

    <template #button="modal">
        <button class="button button--primary" @click="modal.open"><?php i::_e('Inserir nova ocorrência') ?></button>
    </template>

    <template #actions="modal">

        <div class="mobile">
            <div class="button-group">
                <button v-if="step==0" class="button button--text" @click="cancel(modal)"><?php i::_e('Cancelar')?></button>
                <button v-if="step>0" class="button button--text" @click="prev()"><?php i::_e('Voltar')?></button>
                <button v-if="step<5" class="button button--primary button--icon" @click="next()"><?php i::_e('Próximo')?><mc-icon name="next"></mc-icon></button>
                <button v-if="step==5" class="button button--primary" @click="create()"><?php i::_e('Concluir')?></button>
            </div>

            <div class="pagination">
                <div :class="['pagination-item', {active:step==0}]"></div>
                <div :class="['pagination-item', {active:step==1}]"></div>
                <div :class="['pagination-item', {active:step==2}]"></div>
                <div :class="['pagination-item', {active:step==3}]"></div>
                <div :class="['pagination-item', {active:step==4}]"></div>
                <div :class="['pagination-item', {active:step==5}]"></div>
            </div>
        </div>

        <div class="desktop">
            <div class="button-group">
                <button class="button button--text" @click="cancel(modal)"><?php i::_e('Cancelar')?></button>
                <button class="button button--primary" @click="create()"><?php i::_e('Inserir ocorrência')?></button>
            </div>
        </div>

    </template>
</modal>