<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('style.css') ?>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Mail Box</b></h4></div>
    <div class="card">
        <div class="header bg-indigo darken-4">
            <h2>Customer: <?= $customer['name']; ?>
                <small style="color: #aeea00 !important;">We appreciate your ideas and feedback</small>
            </h2>
            <ul class="header-dropdown m-r--5">
                <li><?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?></li>
            </ul>
        </div>
        <div class="body">
            <div class="row" style="margin: 0;">
                <div class="table-responsive" style="width: 100%;">
                    <table class="table">
                        <?php $tokens = explode('##reply##', $message['content']);
                            for ($j = 0; $j < count($tokens); $j++) {
                                $markup = ($j % 2 == 0) ? '<b>C:</b> ' : '<b>A:</b> ';

                                if ($j == 0)
                                    echo '<tr><td>'.$markup.$tokens[$j].'</td></tr>';
                                else if ($j == count($tokens) - 1)
                                    echo '<tr><td>'.str_repeat('&nbsp;', $j*4).$markup.$tokens[$j].'<span class="chip pull-right" style="color: dimgray">'.(((new DateTime($message['created']))->format('d/m/Y H:i'))).'</span></td></tr>';
                                else echo '<tr><td>'.str_repeat('&nbsp;', $j*4).$markup.$tokens[$j].'</td></tr>';
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-action">
            <script type="text/javascript">
                function countRepChars() {
                    var count = document.getElementById("repEr");
                    var message = document.getElementById("repmsg").value;

                    if (200 - message.length == 200)
                        document.getElementById('repsubmit').disabled = true;
                    if (200 - message.length > 0) {
                        count.innerHTML = (200 - message.length).toString().concat(' characters left');
                        count.style.color = 'black';
                        document.getElementById('repsubmit').disabled = false;
                    }
                    else {
                        count.innerHTML = '0 char left!';
                        count.style.color = 'orangered';
                        document.getElementById('repsubmit').disabled = true;
                    }
                }
            </script>
           <?= $this->Form->create($message); ?>
                <textarea name="repmsg" id="repmsg" type="text" class="form-control" required oninput="countRepChars()" rows="5"></textarea>
                <label for="repmsg">Message</label>
                <p id="repEr" class="small">200 characters left</p>
                <button id="repsubmit" type="submit" name="repsubmit" disabled class="waves-effect waves-light btn btn-sm btn-info lighten-2">Reply</button>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
