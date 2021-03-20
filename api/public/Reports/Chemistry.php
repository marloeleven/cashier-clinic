<table class="table chemistry laboratory">
  <?php include 'template/header.php' ?>
  <tr>
    <td class="data" colspan="4">
      <table class="list">
        <tr>
          <td class="fields">
            <table class="procedures">
              <?php 
                foreach ($procedures->slice(0, 30)->chunk(2) as $chunk) {
              ?>
                <tr>
              <?php
                foreach($chunk as $procedure) {
              ?>
                  <td>
                  _____ <?php echo $format::procedureStringLimit($procedure->procedure->name, 24) ?>
                  </td>
                <?php } ?>
                </tr>
              <?php } ?>
            
            </table>
          </td>
          <td class="clinical-history">
            <?php include 'template/clinical_history.php' ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  
</table>