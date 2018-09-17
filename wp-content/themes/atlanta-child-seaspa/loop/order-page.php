<?php
/**
 * Template Name: Order Page
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : 0;


get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<div class="rtl">
		<?php
			$short_text="[cfdb-table form=\"buy3\" headers=\"text-325=first name\" filter=\"order-number=$order_number\"]";
			// echo $short_text;
			// echo do_shortcode( $short_text );
			$short_text1= "[cfdb-html form=\"buy3\" filter=\"order-number=$order_number\"]";
			$short_text1.='א.להזמין שובר מתנה שישלח אלי למייל: ${f1}<br>';
			$short_text1.='ב.לבצע הזמנה לתאריך: ${f2}<br>';
			$short_text1.='שעות מועדפות: ${radio-161} ${radio-997} ${radio-431}<br>';
			$short_text1.='<br>';
			$short_text1.='פרטי לקוח:<br>';
			$short_text1.='הערות:${f6}<br>';
			$short_text1.='דוא"ל:${email-19}<br>';
			$short_text1.='תעודת זהות:${text-160}<br>';
			$short_text1.='שם: ${text-325}<br>';
			$short_text1.='שם משפחה:${text-218}<br>';
			$short_text1.='טלפון :${tel-133}<br>';
			$short_text1.='נייד :${text-903}<br>';
			$short_text1.='עיר:${text-41}<br>';
			$short_text1.='רחוב ומספר בית:${text-999}<br>';
			$short_text1.='קומה:${text-276}<br>';
			$short_text1.='מיקוד:${text-126}<br>';
			$short_text1.='<br>';
			$short_text1.='כתובת למשלוח - (אין צורך למלא במידה וזהה לכתובת המזמין):<br>';
			$short_text1.='<br>';
			$short_text1.='עיר:${text-874}<br>';
			$short_text1.='רחוב ומספר בית:${text-50}<br>';
			$short_text1.='קומה:${text-960}<br>';
			$short_text1.='מיקוד:${text-910}<br>';
			$short_text1.='איש קשר בכתובת:${text-845}<br>';
			$short_text1.='טלפון בכתובת:${text-454}<br>';
			$short_text1.='<br>';
			$short_text1.='כרטיס אשראי:<br>';
			$short_text1.='<br>';
			$short_text1.='סוג כרטיס:${menu-706}<br>';
			$short_text1.='מספר כרטיס אשראי:${text-839}<br>';
			$short_text1.='תוקף:${text-351}<br>';
			$short_text1.='שם מלא בעל הכרטיס:${text-632}<br>';
			$short_text1.='מספר זהות :${text-575}<br>';
			$short_text1.='סכום לסליקה:${text-108}<br>';
			$short_text1.='מחיר:${price}<br>';
			$short_text1.='דגם המוצר: ${product_name}<br>';
			$short_text1.='קראתי והנני מסכים לתנאים והגבלות בחנות (לחץ להצגה):${checkbox-280}<br>';
			$short_text1.='הנני מסכים כי מעת לעת ישלחו אלי באימייל מבצעים והודעות מהחנות.:${checkbox-422}<br>';
			$short_text1.='<h2><a href="${page_url}">קישור למוצר</a><br></h2>';
			$short_text1.='[/cfdb-html]';
			// echo $short_text1."<br>";
			echo do_shortcode( $short_text1 );
		?>
		</div>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;


get_footer();
