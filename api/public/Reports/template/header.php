<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<tr class="header">
    <td colspan="2">GoodShepherd</td>
    <td><?php echo "{$header->main} :: {$header->sub}" ?></td>  
  </tr>
  <tr class="header-description">
    <td colspan="2">
      Laboratory and Health Diagnostics Services, Inc.
    </td>
    <td><?php echo $header->description ?></td>  
  </tr>
  <tr>
    <td class="contact" colspan="3">Tel No. 941-0173 / 948-7593 Cell No. 0917-5683182 (Globe) * 0925-3476888 (Sun)</td>
  </tr>
  <tr class="info">
    <td class="name"><span>Name:</span> <?php echo $format::procedureStringLimit($patient->full_name, 28) ?></td>
    <td><span>ID:</span> <?php echo $patient->id_string ?></td>
    <td><span>Reference ID:</span> <?php echo $header->reference_number ?></td>
  </tr>
  <tr class="info">
    <td><span>Birthdate:</span> <?php echo $patient->birth_date ?></td>
    <td><span>Contact #:</span> <?php echo $patient->contact_number ?></td>
    <td><span>Date:</span> <?php echo $header->created_at ?></td>
  </tr>
  <tr class="info last">
    <td><span>Age:</span> <?php echo $patient->age ?></td>
    <td><span>Gender:</span> <?php echo $patient->gender ?></td>
    <td><span>Requesting Physician:</span> <?php echo $format::procedureStringLimit($header->physician, 30) ?></td>
  </tr>