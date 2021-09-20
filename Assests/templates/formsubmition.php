
<?php
/*
Template Name: Booking
*/
get_header();
$time = $_GET['time'];
$timestamp = strtotime($time);
if(isset($_POST['submit']))
{
  $email = $_POST["email"];
  $treatment = $_POST["treatment"];
  $time = $_POST['time'];
}
?>
<main id="site-content" role="main">

  <form name="form1" method="get" action="https://aneurinj.com/confirmadmin/">
    Enter email:
    <input type="text" name="email">

    Select Treatment:  
    <select name="treatment" >
      <option value="Reflexology">Reflexology</option>
      <option value="Hot Stone Massage">Hot Stone Massage</option>
      <option value="Reiki">Reiki</option>
      <option value="Indian Head Massage">Indian Head Massage</option>
      <option value="Western Acupuncture">Western Acupuncture</option>
    </select>

    Time:<input type="text" name="time" value = "<?php echo $time;?>">

    Timestamp:<input type="text" name="timestamp" value="<?php echo $timestamp;?>">

    <input type="submit" value="Submit">
  </form>
</main>


<?php get_footer();