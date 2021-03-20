<table class="table generic">
  <?php include 'template/header.php' ?>
  <tr>
    <td class="data" colspan="3">
      <table class="list">
      <?php 
        foreach ($procedures->slice(0, 30)->chunk(2) as $chunk) {
      ?>
        <tr>
      <?php
        foreach($chunk as $key => $procedure) {
      ?>
          <td>
            _____ <?php echo $format::procedureStringLimit($procedure->procedure->name) ?>
          </td>
        <?php } ?>
        </tr>
      <?php } ?>
      
      </table>
    </td>
  </tr>
  
</table>