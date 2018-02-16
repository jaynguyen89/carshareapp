<?= $this->Flash->render() ?>
<br><br>
<div class="row">
    <div class="card horizontal z-depth-2" style="min-width: 450px; margin: auto; padding: 10px; border-radius: 15px;">
        <h1 class="card-header text-center" style="color: #304a74;"><i class="material-icons teal-text" style="font-size: 0.75em;">fingerprint</i> Log In</h1>
        <?= $this->Form->create() ?>
        <div id="form-login-username" class="form-group">
            <?= $this->Form->control('username', ['class' => 'form-control', 'name' => 'username', 'id' => 'username', 'type' => 'text', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div id="form-login-password" class="form-group">
            <?= $this->Form->control('password', ['class' => 'form-control', 'name' => 'password', 'id' => 'password', 'type' => 'password', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div>
            <?= $this->Form->button(__('Log In'), ['class' => 'btn btn-block btn-info lighten-2 ripple-effect', 'type' => 'submit']); ?>
        </div>
        <br/>
        <p class="text-center">Forgot <a href="#">username</a> or <a href="#">password</a>?</p>
        <?= $this->Form->end() ?>
    </div>
</div>