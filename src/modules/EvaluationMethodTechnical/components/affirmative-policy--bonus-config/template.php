<?php

use MapasCulturais\i;

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

$this->import('
    mc-icon
    mc-modal
');
?>


<div class="affirmative-policies--quota-configuration">
    <h4 class="bold"><?= i::__('Configuração das Cotas por Categoria') ?></h4>

    <div class="affirmative-policies--quota-configuration__content" v-if="entity.affirmative-policy--bonus-config">
        <div class="fields">
            <label class="field__title">
                <?= i::__('Percentual total de indução:') ?>
                {{totalPercentage}} %
            </label>


        </div>

        <div class="affirmative-policies--quota-configuration__quota" v-for="(quota, index) in entity.quotaConfiguration.rules" :key="index">
            <div class="affirmative-policies--quota-configuration__column">
                <h5 class="field__title--semibold"><?= i::__('Cota') ?> {{index+1}}</h5>
                <select v-model="quota.fieldName">
                    <option class="select__selected-option" v-for="(item, index) in entity.opportunity.affirmativePoliciesEligibleFields" :value="item.fieldName">{{ '#' + item.id + ' ' + item.title }}</option>
                </select>

                <div class="field">
                    <div class="field__column" v-if="getFieldType(quota) === 'select' || getFieldType(quota) === 'multiselect'">
                        <label v-for="option in getFieldOptions(quota)">
                            <input class="input" type="checkbox" :value="option" v-model="quota.eligibleValues" @change="autoSave()">
                            {{option}}
                        </label>
                    </div>

                    <div class="field__column" v-if="getFieldType(quota) === 'checkbox' || getFieldType(quota) === 'boolean'">
                        <label>
                            <input class="input" type="radio" :name="quota.fieldName + ':' + index" :value="true" v-model="quota.eligibleValues" @change="autoSave()">
                            <?= i::__('Sim / Marcado') ?>
                        </label>
                        <label>
                            <input class="input" type="radio" :name="quota.fieldName + ':' + index" :value="false" v-model="quota.eligibleValues" @change="autoSave()">
                            <?= i::__('Não / Desmarcado') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="quota__fields">
                <label class="field__title"><?= i::__('Porcentagem') ?>
                    <div>
                        <input type="number" v-model="quota.percentage" @change="updateRuleQuotas(quota)" min="0" max="100"> %
                    </div>
                </label>
            </div>

            <div class="quota__fields">
                <label class="field__title"><?= i::__('Número de Vagas') ?>
                    <input type="number" v-model="quota.vacancies" @change="updateRuleQuotaPercentage(quota)" min="0" :max="totalVacancies">
                </label>
            </div>
            <div class="quota__trash">
                <mc-confirm-button @confirm="removeConfig(index)">
                    <template #button="{open}">
                        <div class="field__trash button button--md button--text-danger button-icon">
                            <mc-icon class="danger__color" name="trash" @click="open()"></mc-icon>
                        </div>
                    </template>
                    <template #message="message">
                        <?= i::__('Deseja deletar a cota?') ?>
                    </template>
                </mc-confirm-button>
            </div>
        </div>
    </div>
    <div class="affirmative-policies--quota-configuration__footer">
        <button @click="addConfig();" class="button button--primary button--icon">
            <mc-icon name="add"></mc-icon>
            <label v-if="entity.quotaConfiguration && entity.quotaConfiguration.rules.length > 0">
                <?php i::_e("Adicionar categoria") ?>
            </label>
            <label v-else>
                <?php i::_e("Configurar Cotas por Categoria") ?>
            </label>
        </button>
    </div>
</div>