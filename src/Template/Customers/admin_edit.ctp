<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?= $this->Html->css('style.css') ?>
<script type="text/javascript">
    function checkInputs() {
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;
        var add = document.getElementById('address').value;

        var err = document.getElementById('saveMsg');
        var submit = document.getElementById('submit');

        if (name !== '<?= $admin['name']; ?>' || email !== '<?= $admin['email']; ?>' ||
            phone !== '<?= $admin['phone']; ?>' || add !== '<?= $admin['address']; ?>') {
            submit.disabled = false;
            err.style.display = 'none';
        }
        else {
            submit.disabled = true;
            err.style.display = '';
            err.innerHTML = 'Nothing new to save!';
        }
    }
</script>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Update Profile</b></h4></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <small style="color: #aeea00 !important;">Please note: All fields are required.</small>
                </h2>
                <ul class="header-dropdown m-r--5"><li><?= $this->Html->link(__('Cancel'), ['controller' => 'users', 'action' => 'view'], ['class' => 'waves-effect waves-light btn btn-sm btn-danger']) ;?></li></ul>
            </div>
            <?= $this->Form->create($admin); ?>
            <div class="card-body">
                <p class="font-bold" style="font-size: larger">General Information</p>
                <table class="table" style="margin-bottom: 0;">
                    <tr><th scope="row">Staff Ref.</th>
                        <td><input value="X2345678" label="false" class="form-control" id="staffref" type="text" disabled /></td>
                    </tr>
                    <tr><th scope="row">Full Name</th>
                        <td><?= $this->Form->control('name', array('value' => $admin['name'], 'class' => 'form-control', 'label' => false, 'id' => 'name', 'type' => 'text', 'required' => true, 'onchange' => 'checkInputs()')); ?></td>
                    </tr>
                    <tr><th scope="row">Email</th>
                        <td><?= $this->Form->control('email', array('value' => $admin['email'], 'class' => 'form-control', 'label' => false, 'id' => 'email', 'type' => 'text', 'required' => true, 'onchange' => 'checkInputs()')); ?></td>
                    </tr>
                    <tr><th scope="row">Phone</th>
                        <td><?= $this->Form->control('phone', array('value' => $admin['phone'], 'class' => 'form-control', 'label' => false, 'id' => 'phone', 'type' => 'text', 'required' => true, 'onchange' => 'checkInputs()')); ?></td>
                    </tr>
                    <tr><th scope="row">Home Address</th>
                        <td><?= $this->Form->control('address', array('value' => $admin['address'], 'class' => 'form-control', 'label' => false, 'id' => 'address', 'type' => 'text', 'required' => true, 'onchange' => 'checkInputs()')); ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Done'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'submit', 'type' => 'submit', 'disabled' => true]); ?>
                <p style="display: none; color: orangered;" class="small" id="saveMsg"></p>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
