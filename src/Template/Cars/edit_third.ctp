<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>

<script type="text/javascript">
    function switchAudio() {
        var checkbox = document.getElementById('selectaudio');
        var audioText = document.getElementById('audiotext');

        audioText.disabled = !checkbox.checked;
    }

    function checkAudioText() {
        var audioText = document.getElementById('audiotext');
        var audioDesc = document.getElementById('audiodesc');
        var audioEr = document.getElementById('audioEr');

        if (audioText.value.length === 0)
            audioEr.style.display = 'none';
        else {
            var audio = toUpper(audioText.value);
            var desc = audioDesc.value;
            var tokens = desc.split(', ');

            if (tokens.indexOf(audio) !== -1) {
                audioEr.innerHTML = 'Record already added.';
                audioEr.style.display = '';
            }
            else {
                var currentVal = audioDesc.value;
                audioDesc.value = (currentVal === '' ? audio : currentVal + ', ' + audio);
                audioText.value = '';

                var audios = <?= json_encode($audioOptions); ?>;

                if (audios.indexOf(audio) !== -1) {
                    var ele = document.getElementById(audio.replace(/\s/g, ''));
                    ele.style.display = 'none';
                }
            }
        }
    }

    function switchConv() {
        var checkbox = document.getElementById('selectconv');
        var convText = document.getElementById('convtext');

        convText.disabled = !checkbox.checked;
    }

    function checkConvText() {
        var convText = document.getElementById('convtext');
        var convEr = document.getElementById('convEr');
        var convenience = document.getElementById('convenience');

        if (convText.value.length === 0)
            convEr.style.display = 'none';
        else {
            var conv = toUpper(convText.value);
            var conVal = convenience.value;
            var tokens = conVal.split(', ');

            if (tokens.indexOf(conv) !== -1) {
                convEr.innerHTML = 'Record already added.';
                convEr.style.display = '';
            }
            else {
                var currentVal = convenience.value;
                convenience.value = (currentVal === '' ? conv : currentVal + ', ' + conv);
                convText.value = '';

                var convs = <?= json_encode($convOptions); ?>;

                if (convs.indexOf(conv) !== -1) {
                    var ele = document.getElementById(conv.replace(/\s/g, ''));
                    ele.style.display = 'none';
                }
            }
        }
    }

    function switchSafety() {
        var checkbox = document.getElementById('selectsafety');
        var safeText = document.getElementById('safetext');

        safeText.disabled = !checkbox.checked;
    }

    function checkSafeText() {
        var safeText = document.getElementById('safetext');
        var safety = document.getElementById('safety');
        var safeEr = document.getElementById('safeEr');

        if (safeText.value.length === 0)
            safeEr.style.display = 'none';
        else {
            var safe = toUpper(safeText.value);
            var safeVal = safety.value;
            var tokens = safeVal.split(', ');

            if (tokens.indexOf(safe) !== -1) {
                safeEr.innerHTML = 'Record already added.';
                safeEr.style.display = '';
            }
            else {
                var currentVal = safety.value;
                safety.value = (currentVal === '' ? safe : currentVal + ', ' + safe);
                safeText.value = '';

                var safeties = <?= json_encode($safetyOptions); ?>;

                if (safeties.indexOf(safe) !== -1) {
                    var ele = document.getElementById(safe.replace(/\s/g, ''));
                    ele.style.display = 'none';
                }
            }
        }
    }

    function switchLivi() {
        var checkbox = document.getElementById('selectlivi');
        var liviText = document.getElementById('livitext');

        liviText.disabled = !checkbox.checked;
    }

    function checkLiviText() {
        var liviText = document.getElementById('livitext');
        var livi = document.getElementById('lightsview');
        var liviEr = document.getElementById('liviEr');

        if (liviText.value.length === 0)
            liviEr.style.display = 'none';
        else {
            var livis = toUpper(liviText.value);
            var liviVal = livi.value;
            var tokens = liviVal.split(', ');

            if (tokens.indexOf(livis) !== -1) {
                liviEr.innerHTML = 'Record already added.';
                liviEr.style.display = '';
            }
            else {
                var currentVal = livi.value;
                livi.value = (currentVal === '' ? livis : currentVal + ', ' + livis);
                liviText.value = '';

                var liviss = <?= json_encode($liviOptions); ?>;

                if (liviss.indexOf(livis) !== -1) {
                    var ele = document.getElementById(livis.replace(/\s/g, ''));
                    ele.style.display = 'none';
                }
            }
        }
    }

    function switchOther() {
        var checkbox = document.getElementById('selectother');
        var otherText = document.getElementById('othertext');

        otherText.disabled = !checkbox.checked;
    }

    function checkOtherText() {
        var otherText = document.getElementById('othertext');
        var others = document.getElementById('otherspecs');
        var otherEr = document.getElementById('otherEr');



        if (otherText.value.length === 0)
            otherEr.style.display = 'none';
        else {
            var other = toUpper(otherText.value);
            var otherVal = others.value;
            var tokens = otherVal.split(', ');

            if (tokens.indexOf(other) !== -1) {
                otherEr.innerHTML = 'Record already added.';
                otherEr.style.display = 'none';
            }
            else {
                var currentVal = others.value;
                others.value = (currentVal === '' ? other : currentVal + ', ' + other);
                otherText.value = '';

                var otherss = <?= json_encode($otherOptions); ?>;

                if (otherss.indexOf(other) !== -1) {
                    var ele = document.getElementById(other.replace(/\s/g, ''));
                    ele.style.display = 'none';
                }
            }
        }
    }

    function toUpper(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function addToAudio(ele) {
        var textarea = document.getElementById('audiodesc');
        var value = textarea.value;
        var eleText = ele.innerHTML;
        textarea.value = (value === '' ? eleText : value + ', ' + eleText);
        var container = document.getElementById(eleText.replace(/\s/g, ''));
        container.style.display = 'none';
    }

    function addToConv(ele) {
        var textarea = document.getElementById('convenience');
        var value = textarea.value;
        var eleText = ele.innerHTML;
        textarea.value = (value === '' ? eleText : value + ', ' + eleText);
        var container = document.getElementById(eleText.replace(/\s/g, ''));
        container.style.display = 'none';
    }

    function addToSafety(ele) {
        var textarea = document.getElementById('safety');
        var value = textarea.value;
        var eleText = ele.innerHTML;
        textarea.value = (value === '' ? eleText : value + ', ' + eleText);
        var container = document.getElementById(eleText.replace(/\s/g, ''));
        container.style.display = 'none';
    }

    function addToLivi(ele) {
        var textarea = document.getElementById('lightsview');
        var value = textarea.value;
        var eleText = ele.innerHTML;
        textarea.value = (value === '' ? eleText : value + ', ' + eleText);
        var container = document.getElementById(eleText.replace(/\s/g, ''));
        container.style.display = 'none';
    }

    function addToOther(ele) {
        var textarea = document.getElementById('otherspecs');
        var value = textarea.value;
        var eleText = ele.innerHTML;
        textarea.value = (value === '' ? eleText : value + ', ' + eleText);
        var container = document.getElementById(eleText.replace(/\s/g, ''));
        container.style.display = 'none';
    }
</script>
<style>
    .disabled {
        pointer-events: none;
        cursor: crosshair;
    }
</style>

<?= $this->Flash->render(); ?>
<div class="container">
    <div class="text-center teal-text darken-4"><h2><b>Update Car Information: Step 3</b></h2></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <div class="progress" style="width: 75%; margin-bottom: 0;">
                        <div class="progress-bar bg-teal progress-bar-striped" role="progressbar" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100"
                             style="width: 54%">Step 3 of 5</div>
                    </div>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><?= $this->Html->link(__('Finish'), ['action' => 'delete', $car['id']], ['confirm' => __('{0} will be completely removed from the system. Are you sure to cancel?', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => 'waves-effect waves-light btn btn-sm btn-danger disabled']); ?></li>
                </ul>
            </div>
            <?= $this->Form->create($car); ?>
            <div class="card-body">
                <div class="table" style="margin: 0;">
                    <table class="table-responsive" style="margin: 0;">
                        <tr id="traudio"><th scope="row">Audio</th>
                            <td><div class="row" style="width: 90%"><?php if (!$audioOptions) echo '<span class="chip bg-grey" style="margin: 2px">No suggestions</span>'; else {
                                $audiotoks = explode(', ', $car['audiodesc']); $all = (count($audiotoks) == count($audioOptions)) ? true : false;
                                if ($all) echo '<span class="chip" style="margin: 2px">No suggestions</span>'; else foreach ($audioOptions as $audio): ?>
                                        <span class="chip bg-indigo" style="margin: 2px; <?= strpos($car['audiodesc'], $audio) == false ? 'display: none' : ''; ?>" id="<?= preg_replace('/\s+/', '', $audio); ?>"><a href="#" onclick="addToAudio(this)"><?= $audio; ?></a></span>
                                    <?php endforeach; } ?></div>
                                <?= $this->Form->textarea('audiodesc', ['class' => 'form-control', 'placeholder' => 'Click suggestions to add here', 'type' => 'text', 'label' => false, 'id' => 'audiodesc']); ?></td>
                            <td style="width: 200px"><input type="checkbox" name="selectaudio" value="selectaudio" id="selectaudio" onclick="switchAudio()"><label for="selectaudio" style="color: black;">Add Another</label>
                                <?= $this->Form->control('audio', ['type' => 'text', 'label' => false, 'placeholder' => 'Audio', 'id' => 'audiotext', 'disabled' => true, 'onchange' => 'checkAudioText()']); ?>
                                <p class="small" id="audioEr" style="color: orangered; display: none"></p></td></tr>
                        <tr id="trconv"><th scope="row">Convenience</th>
                            <td><div class="row" style="width: 90%"><?php if (!$convOptions) echo '<span class="chip" style="margin: 2px">No suggestions</span>'; else {
                                $convtoks = explode(', ', $car['convenience']); $all = (count($convtoks) == count($convOptions)) ? true : false;
                                if ($all) echo '<span class="chip" style="margin: 2px">No suggestions</span>'; else foreach ($convOptions as $conv): ?>
                                        <span class="chip bg-indigo" style="margin: 2px; <?= strpos($car['convenience'], $conv) == false ? 'display: none' : ''; ?>" id="<?= preg_replace('/\s+/', '', $conv); ?>"><a href="#" onclick="addToConv(this)"><?= $conv; ?></a></span>
                                    <?php endforeach; } ?></div>
                                <?= $this->Form->textarea('convenience', ['class' => 'form-control', 'placeholder' => 'Click on suggestions to add here', 'type' => 'text', 'label' => false, 'id' => 'convenience']); ?></td>
                            <td><input type="checkbox" name="selectconv" value="selectconv" id="selectconv" onclick="switchConv()"><label for="selectconv" style="color: black;">Add Another</label>
                                <?= $this->Form->control('conv', ['type' => 'text', 'label' => false, 'placeholder' => 'Convenience', 'id' => 'convtext', 'disabled' => true, 'onchange' => 'checkConvText()']); ?>
                                <p class="small" id="convEr" style="color: orangered; display: none"></p></td></tr>
                        <tr id="trsafe"><th scope="row">Safety</th>
                            <td><div class="row" style="width: 90%"><?php if (!$safetyOptions) echo '<span class="chip bg-grey" style="margin: 2px">No suggestions</span>'; else {
                                $safetoks = explode(', ', $car['safety']); $all = (count($safetoks) == count($safetyOptions)) ? true : false;
                                if ($all) echo '<span class="chip" style="margin: 2px">No suggestions</span>'; else foreach ($safetyOptions as $safety): ?>
                                        <span class="chip bg-indigo" style="margin: 2px; <?= strpos($car['safety'], $safety) == false ? 'display: none' : ''; ?>" id="<?= preg_replace('/\s+/', '', $safety); ?>"><a href="#" onclick="addToSafety(this)"><?= $safety; ?></a></span>
                                    <?php endforeach; } ?></div>
                                <?= $this->Form->textarea('safety', ['class' => 'form-control', 'placeholder' => 'Click on suggestions to add here', 'type' => 'text', 'label' => false, 'id' => 'safety']); ?></td>
                            <td><input type="checkbox" name="selectsafety" value="selectsafety" id="selectsafety" onclick="switchSafety()"><label for="selectsafety" style="color: black;">Add Another</label>
                                <?= $this->Form->control('safe', ['type' => 'text', 'label' => false, 'placeholder' => 'Safety', 'id' => 'safetext', 'disabled' => true, 'onchange' => 'checkSafeText()']); ?>
                                <p class="small" id="safeEr" style="color: orangered; display: none"></p></td></tr>
                        <tr id="trlivi"><th scope="row">Light & View</th>
                            <td><div class="row" style="width: 90%"><?php if (!$liviOptions) echo '<span class="chip bg-grey" style="margin: 2px">No suggestions</span>'; else {
                                $livitoks = explode(', ', $car['lightsview']); $all = (count($livitoks) == count($liviOptions)) ? true : false;
                                if ($all) echo '<span class="chip" style="margin: 2px">No suggestions</span>'; else foreach ($liviOptions as $livi): ?>
                                        <span class="chip bg-indigo" style="margin: 2px; <?= strpos($car['lightsview'], $livi) == false ? 'display: none' : ''; ?>" id="<?= preg_replace('/\s+/', '', $livi); ?>"><a href="#" onclick="addToLivi(this)"><?= $livi; ?></a></span>
                                    <?php endforeach; } ?></div>
                                <?= $this->Form->textarea('lightsview', ['class' => 'form-control', 'placeholder' => 'Click on suggestions to add here', 'type' => 'text', 'label' => false, 'id' => 'lightsview']); ?></td>
                            <td><input type="checkbox" name="selectlivi" value="selectlivi" id="selectlivi" onclick="switchLivi()"><label for="selectlivi" style="color: black;">Add Another</label>
                                <?= $this->Form->control('livi', ['type' => 'text', 'label' => false, 'placeholder' => 'L&V', 'required' => true, 'id' => 'livitext', 'disabled' => true, 'onchange' => 'checkLiviText()']); ?>
                                <p class="small" id="liviEr" style="color: orangered; display: none"></p></td></tr>
                        <tr id="trother"><th scope="row">Other Specs</th>
                            <td><div class="row" style="width: 90%"><?php if (!$otherOptions) echo '<span class="chip bg-grey" style="margin: 2px">No suggestions</span>'; else {
                                $othertoks = explode(', ', $car['otherspecs']); $all = (count($othertoks) == count($otherOptions)) ? true : false;
                                if ($all) echo '<span class="chip" style="margin: 2px">No suggestions</span>'; else foreach ($otherOptions as $other): ?>
                                        <span class="chip bg-indigo" style="margin: 2px; <?= strpos($car['otherspecs'], $other) == false ? 'display: none' : ''; ?>" id="<?= preg_replace('/\s+/', '', $other); ?>"><a href="#" onclick="addToOther(this)"><?= $other; ?></a></span>
                                    <?php endforeach; } ?></div>
                                <?= $this->Form->textarea('otherspecs', ['class' => 'form-control', 'placeholder' => 'Click on suggestions to add here', 'type' => 'text', 'label' => false, 'id' => 'otherspecs']); ?></td>
                            <td><input type="checkbox" name="selectother" value="selectother" id="selectother" onclick="switchOther()"><label for="selectother" style="color: black;">Add Another</label>
                                <?= $this->Form->control('other', ['type' => 'text', 'label' => false, 'placeholder' => 'Specs', 'id' => 'othertext', 'disabled' => true, 'onchange' => 'checkOtherText()']); ?>
                                <p class="small" id="otherEr" style="color: orangered; display: none"></p></td></tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Next Step'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'nextstep', 'type' => 'submit', 'style' => 'width: 200px;']); ?>
                <?= $this->Form->end(); ?>
                <?= $this->Html->link(__('Save for later'), ['action' => 'revive'], ['class' => 'btn btn-lg btn-warning lighten-2 waves-effect waves-light', 'style' => 'width: 200px;']); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
