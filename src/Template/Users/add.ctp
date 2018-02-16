<script>
    var pwcheck = false;
    function checkPw() {
        var pass = document.getElementById('password').value;
        var message = document.getElementById('pwmessage');

        var hasUp = !(pass.toLowerCase() == pass);
        //var hasNum = /0-9/.test(pass);
        var hasSp = !(/\s/g.test(pass));
        var hasSign = !(/^[a-zA-Z0-9]*$/.test(pass));
        var len = (pass.length > 7 && pass.length < 17);
        var line = "";

        if (!len)
            line = line.concat("Length 8-16. ");

        if (!hasUp)
            line = line.concat("Require uppercase chars! ");

        //if (!hasNum)
            //line = line.concat("Require numbers! ");

        if (!hasSp)
            line = line.concat("Remove whitespace! ");

        if (!hasSign)
            line = line.concat("Require special chars!");

        pwcheck = (hasUp && hasSp && hasSign && len);

        if (pwcheck)
            message.innerHTML = "";
        else
            message.innerHTML = line;
    }

    function pwVerify() {
        var submit = document.getElementById('submit');
        var confirm = document.getElementById('confirm').value;
        var pass = document.getElementById('password').value;
        var message = document.getElementById('pcmessage');

        if (pass.localeCompare(confirm) == 0 && pwcheck) {
            submit.disabled = false;
            message.innerHTML = '';
        }
        else {
            message.innerHTML = 'Password not matched. Re-type password!';
            submit.disabled = true;
        }
    }
    
    function enableStaffid() {
        var checkbox = document.getElementById('admin');

        if (checkbox.checked) {
            document.getElementById('staffid').disabled = false;
            document.getElementById('staffid').setAttribute('required', true);
        }
        else {
            document.getElementById('staffid').disabled = true;
            document.getElementById('staffid').removeAttribute('required');
        }
    }
</script>

<?= $this->Flash->render() ?>

<br><br>
<div class="row">
    <div class="card horizontal z-depth-2" style="min-width: 450px; margin: auto; padding: 10px; border-radius: 15px;">
        <h1 class="card-header text-center" style="color: #304a74;"><i class="material-icons teal-text" style="font-size: 0.75em;">people</i> Sign up</h1>
        <?= $this->Form->create($user) ?>
        <div id="form-admin-checkbox" class="form-group">
            <input type="checkbox" name="admin" value="admin" id="admin" onclick="enableStaffid()"><label for="admin" style="color: black; margin-top: 15px;">I am Admin</label>
            <?= $this->Form->control('staffid', ['class' => 'form-control', 'name' => 'staffid', 'id' => 'staffid', 'type' => 'text', 'disabled' => true]) ?>
            <span id="spanadmin" class="form-highlight"></span>
            <span id="baradmin" class="form-bar"></span>
        </div>
        <div id="form-login-username" class="form-group">
            <?= $this->Form->control('username', ['class' => 'form-control', 'name' => 'username', 'id' => 'username', 'type' => 'text', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div id="form-login-password" class="form-group">
            <?= $this->Form->control('password', ['class' => 'form-control', 'name' => 'password', 'id' => 'password', 'type' => 'password', 'required' => true, 'onkeyup' => 'checkPw()']) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
            <p id="pwmessage" class="text-right" style="color: coral; font-size: small;"></p>
        </div>
        <div id="form-login-password" class="form-group">
            <?= $this->Form->control('confirm password', ['class' => 'form-control', 'name' => 'confirm', 'id' => 'confirm', 'type' => 'password', 'required' => true, 'onkeyup' => 'pwVerify()']) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
            <p id="pcmessage" class="text-right" style="color: coral; font-size: small;"></p>
        </div>
        <div id="form-login-username" class="form-group">
            <?= $this->Form->control('name', ['class' => 'form-control', 'name' => 'name', 'id' => 'name', 'type' => 'text', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div id="form-login-username" class="form-group">
            <?= $this->Form->control('email', ['class' => 'form-control', 'name' => 'email', 'id' => 'email', 'type' => 'text', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div id="form-login-username" class="form-group">
            <?= $this->Form->control('phone', ['class' => 'form-control', 'name' => 'phone', 'id' => 'phone', 'type' => 'text', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div id="form-login-username" class="form-group">
            <?= $this->Form->control('address', ['class' => 'form-control', 'name' => 'address', 'id' => 'address', 'type' => 'text', 'required' => true]) ?>
            <span class="form-highlight"></span>
            <span class="form-bar"></span>
        </div>
        <div>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-block btn-info lighten-2 ripple-effect', 'type' => 'submit', 'id' => 'submit', 'disabled' => true]); ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
