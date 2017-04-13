<?php
/**
 * Created by PhpStorm.
 * User: iftekar
 * Date: 27/3/17
 * Time: 6:31 PM
*/

$url = 'https://api.sendgrid.com/';
$user = 'nexmed';
$pass = 'zxcvbn*6767';

/*$json_string = array(

    'to' => array(
        'debasiskar007@gmail.com', 'iftekarkta@gmail.com'
    ),
    'category' => 'test_category'
);


$params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'x-smtpapi' => json_encode($json_string),
    'to'        => 'debasiskar007@gmail.com',
    'subject'   => 'testing from curl'.time(),
    'html'      => '<div align="center" style="font: 14px/20px Arial,Helvetica,sans-serif; color: rgb(0, 0, 0); text-decoration: none;">
<table width="635" cellspacing="10" cellpadding="10" border="0">
    <tbody>
        <tr>
            <td colspan="2"><img height="79" width="633" src="http://spectrumiq.com/site/iqmail_images/emlHeader.jpg" alt="" /></td>
        </tr>
        <tr>
            <td width="436" align="left">
            <div align="center">
            <p><img height="144" width="410" src="http://spectrumiq.com/site/iqmail_images/iqp.jpg" alt="" /></p>
            <p style="font-size: medium; text-align: center; color: rgb(24, 99, 228);"><strong>Websites | eCommerce | Software Development</strong></p>
            <p style="font-size: large; text-align: left;"><span style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><strong>Find out if a new Influx IQ Website is the right move for your company. <a style="color: rgb(255, 124, 0);" href="http://spectrumiq.com/site/influxlanding/customer">Click here to learn more</a></strong></span><br />
            <br />
            <span style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><strong>We offer a seamless bridge</strong> between your ideas and the design/development process. Our services include web design and development that is fully integrated with the latest in social media and search engine optimization!<br />
            <br />
            The <strong>Influx IQ Platform</strong> is a revolution in website marketing. Our technology makes SEO super easy. We are also fully integrated with 49 of the social networks and are 100% RSS compatible so syndicating your content is a breeze. This is not just a regular website... this is a turbo-charged marketing engine we have built from the ground up to get results!</span></p>
            <p style="text-align: left;"><span style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><strong>Find out if a new Influx IQ Website is the right move for your company. <a style="color: rgb(255, 124, 0);" href="http://spectrumiq.com/site/influxlanding/customer">Click here to learn more</a></strong><br />
            <br />
            Owning and operating a successful website has become more of a challenge as the Internet has evolved. There are several features that you need to survive in the online environment today. We offer fully supported services and state of the art web technology as well as the latest trends and solutions for mobile devices.</span></p>
            <p><span style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><a style="color: rgb(255, 124, 0);" href="http://spectrumiq.com/site/influxlanding/customer"><span style="text-align: center; font-size: large; color: rgb(255, 124, 0);"><strong>Click Here to Get Started</strong></span></a></span></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><strong>About Influx IQ</strong></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><strong>I</strong><span style="font-size: 14px; text-align: left;"><strong>nflux IQ specializes in both web and mobile application development</strong>, which gives us the opportunity to provide your business with the tools and visibility to succeed. </span></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><span style="font-size: 14px; text-align: left;">The leaders of our company are authorities in the online internet industry. Speaking at major conferences such as the IT Summit and AffCon they are educators amongst their colleagues Every single hour we work is documented through our unique incremental reporting program.</span></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><span style="font-size: 14px; text-align: left;"><strong>Our proven web development process and marketing expertise make us the perfect partner to grow your web property. </strong>Through intelligent design and seasoned experience your next venture will have the backbone it takes to go vertical.</span></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><span style="font-size: 14px; text-align: left;">Our web development specialists inject new flow and efficiency into your daily web marketing and operations. As experts in complex corporate / enterprise web development and sophisticated portal site development, we build robust, flexible, scalable and effective web applications.</span></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><span style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><strong>Find out if a new Influx IQ Website is the right move for your company. <a style="color: rgb(255, 124, 0);" href="http://spectrumiq.com/site/influxlanding/customer">Click here to learn more</a></strong></span></p>
            <p style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: center;"><strong><span style="font-size: small;">To not receive email from this source again <a href="http://www.spectrumiq.com/site/cancel.php">click here.</a></span></strong></p>
            </div>
            </td>
            <td width="188" valign="top" bgcolor="#eeffff" align="center">
            <div align="center">
            <p style="font-size: 14px; text-align: left;"><strong>Brought to you by:</strong></p>
            <p style="font-size: 14px; text-align: center;"><strong>[business name]</strong></p>
            <p style="font-size: 14px; text-align: left;"><strong>The Influx IQ Dev group</strong> was founded by Beto Paredes, software architect extraordinaire who has worked on over 3,000 projects to date.</p>
            <p style="font-size: 14px; text-align: left;">The team at<strong> InfluxIQ </strong>was <strong> carefully selected</strong> for their individual skill and talents. <strong>The experts that we have brought together</strong> have the experience expected from a professional team. The executive management has worked with countless developers, designers and marketing professionals over the years.<br />
            <br />
            <strong>We welcome a challenge!</strong> We look forward to this relationship with you and your company. We will prove we have the kind of chemistry you are looking for!</p>
            </div>
            <p><span style="line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><a style="color: rgb(255, 124, 0);" href="http://spectrumiq.com/site/influxlanding/customer"><span style="text-align: center; color: rgb(255, 124, 0);"><strong>Get Started</strong></span></a></span><strong><span style="font-size: 14px;"> today and learn more about<br />
            how the Influx IQ Development Group can enhance your business</span></strong></p>
            <p><img height="632" width="175" src="http://spectrumiq.com/site/iqmail_images/sidebar.jpg" alt="" /></p>
            <p><span style="font-size: 16px; line-height: 16pt; color: rgb(51, 51, 51); text-align: left;"><a style="color: rgb(255, 124, 0);" href="http://spectrumiq.com/site/influxlanding/customer"><span style="text-align: center; font-size: medium; color: rgb(255, 124, 0);"><strong>Get Started</strong></span></a></span><strong><span style="font-size: medium;"> tod</span>ay!</strong></p>
            </td>
        </tr>
    </tbody>
</table>
</div>
<p>&nbsp;</p>',
    'text'      => 'testing body',
    'from'      => 'techsupport@nexmedsolutions.com',
    //'files[1_0_250_1_1_0.jpg]' => '/home/nexmed/public_html/uploads/documents/1_0_250_1_1_0.jpg'
    'files[example_021.pdf]' =>  '@'.ai_cascadepath('uploads/documents').'/example_021.pdf'
);
echo ai_cascadepath('uploads/documents').'/example_021.pdf';*/

//echo $filePath = dirname(__FILE__);
/*
$request =  $url.'api/mail.send.json';

// Generate curl request
$session = curl_init($request);

// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);

// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
curl_setopt($session, CURLOPT_SSLVERSION, 6);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

// print everything out
print_r($response);*/



/*identity=Sender_Address1&name=Grant&email=grant.hull%40sendgrid.com&address=929_Pearl_Stree&city=Boulder&state=Colorado&zip=80302&country=US&api_user=your_sendgrid_username&api_key=your_sendgrid_password*/


/*echo $request =  $url.'api/newsletter/identity/add.json';

$params = array(
    'identity'   => 'test sender',
    'name'   => 'This is Tester',
    'email'   => 'debasis@nexmedsolutions.com',
    'replyto'   => 'debasis@nexmedsolutions.com',
    'address'=>'929_Pearl_Stree',
    'city'=>'Boulder',
    'state'=>'Colorado',
    'zip'=>'80302',
    'country'=>'US',
    //'x-smtpapi' => json_encode($json_string),
    'api_user'  => $user,
    'api_key'   => $pass,
);
// Generate curl request
$session = curl_init($request);

// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);

// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
curl_setopt($session, CURLOPT_SSLVERSION, 5);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

// print everything out
print_r($response);*/




//$curl = curl_init();
//
//curl_setopt_array($curl, array(
//    CURLOPT_URL => "https://usX.api.mailchimp.com/3.0/lists",
//    CURLOPT_USER =>'anystring:748053d8916b843dee28535b03ca80ea-us15' ,
//    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_ENCODING => "",
//    CURLOPT_MAXREDIRS => 10,
//    CURLOPT_TIMEOUT => 30,
//    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//    CURLOPT_CUSTOMREQUEST => "POST",
//    CURLOPT_POSTFIELDS => "{\"name\":\"My Sender I9987D\",\"from\":{\"email\":\"debasiskar007@gmail.com\",\"name\":\"Example INC\"},\"reply_to\":{\"email\":\"debasiskar007@gmail.com\",\"name\":\"Example INC\"},\"address\":\"123 Elm St.\",\"address_2\":\"Apt. 456\",\"city\":\"Denver\",\"state\":\"Colorado\",\"zip\":\"80202\",\"country\":\"United States\"}",
//    CURLOPT_HTTPHEADER => array(
//        //"authorization: Bearer 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",
//        "content-type: application/json"
//    ),
//));




$apiKey = '748053d8916b843dee28535b03ca80ea-us15';
$listID = 'InsertMailChimpListID';

// MailChimp API URL
//$memberID = md5(strtolower($email));
$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
echo $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/';
echo $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/d477376151';

echo $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns/745aaf832f/content';
echo $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns/745aaf832f/actions/send';
echo $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/campaigns/';

// member information
$json = '{"members": [{"email_address": "urist.mcvankab@freddiesjokes.com", "status": "subscribed"}, {"email_address": "urist.mcvankab+1@freddiesjokes.com", "status": "subscribed"}, {"email_address": "urist.mcvankab+2@freddiesjokes.com", "status_if_new": "subscribed"}], "update_existing": true}';


$json = '{"recipients":{"list_id":"d477376151"},"type":"regular","settings":{"subject_line":"Your Purchase Receipt","reply_to":"iftekar001@nexmedsolutions.com","from_name":"Customer Service"}}';

//$json = '{"html": "<p>The HTML to use for the saved campaign<./p>"}';
/*$json = json_encode([
    'name' => "Freddies Favorite Hats",
    //'status'        => 'subscribed',
    'contact'  => [
        'company'     => 'MailChimp',
        'address1'     => '675 Ponce De Leon Ave NE',
        'address2'     => 'Suite 5000',
        'city'     => 'Atlanta',
        'state'     => 'GA',
        'zip'     => '30308',
        'country'     => 'US',
        'phone'     => '1234567890',
        ],
    "permission_reminder"=>"Youre receiving this email because you signed up for updates about Freddies newest hats.",
    "campaign_defaults"=>[
        "from_name"=>"Freddie",
        "from_email"=>"freddie@freddiehats.com",
        "subject"=>"",
        "language"=>"en"],
    "email_type_option"=>true,
    "notify_on_subscribe"=> "",
    "notify_on_unsubscribe"=> "",
    "visibility"=> "pub"

]);

// send a HTTP POST request with curl
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
print_r($result);
*/


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
print_r($result);


exit;



















$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sendgrid.com/v3/senders",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\"nickname\":\"My Sender I9987D\",\"from\":{\"email\":\"debasiskar007@gmail.com\",\"name\":\"Example INC\"},\"reply_to\":{\"email\":\"debasiskar007@gmail.com\",\"name\":\"Example INC\"},\"address\":\"123 Elm St.\",\"address_2\":\"Apt. 456\",\"city\":\"Denver\",\"state\":\"Colorado\",\"zip\":\"80202\",\"country\":\"United States\"}",
    CURLOPT_HTTPHEADER => array(
        "authorization: Bearer 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",
        "content-type: application/json"
    ),
));

//$response = curl_exec($curl);
$err = curl_error($curl);
print_r($response);