<?php
use MapasCulturais\i;
$this->import('tabs panel--entity-tabs');

$profile = $app->user->profile;
?>

<div class="panel__row">
    <h1>
        <iconify icon="mdi:account-multiple-outline"></iconify>
        <?= i::__('Meus agentes') ?>
    </h1>
    <a class="panel__help-link" href="#"><?=i::__('Ajuda')?></a>
</div>
<div class="panel__row">
    <div class="justify-between">
        <h2><?= sprintf(i::__('Olá, %s'), $profile->name) ?></h2>
        <a class="button is-large is-primary" href="#">
            <iconify icon="mdi:account"></iconify>
            <span><?=i::__('Acessar meu perfil')?></span>
        </a>
    </div>
</div>

<?php $this->applyTemplateHook('tabs', 'before') ?>
<img src="<?php $this->asset('img/default-avatar.png') ?>">
<panel--entity-tabs type="agent"></panel--entity-tabs>
<?php $this->applyTemplateHook('tabs', 'after') ?>