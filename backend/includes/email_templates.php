<?php
/**
 * Email templates for ANAYD website
 */

/**
 * Get the welcome email template for newsletter subscribers
 * 
 * @param string $email The subscriber's email address
 * @return array Array containing email subject and body
 */
function get_newsletter_welcome_email($email) {
    $subject = "Welcome to ANAYD Newsletter";
    
    $body = "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Welcome to ANAYD Newsletter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .social-links {
            text-align: center;
            margin-top: 15px;
        }
        .social-links a {
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <img src=\"https://anayd.org/asset/images/favicon.jpg\" alt=\"ANAYD Logo\" class=\"logo\">
        <h1>Welcome to the ANAYD Family!</h1>
    </div>
    
    <div class=\"content\">
        <p>Dear Subscriber,</p>
        
        <p>Thank you for subscribing to the ANAYD newsletter! We're excited to have you join our community of supporters who are passionate about improving the lives of adolescents and young people across Africa.</p>
        
        <p>As a subscriber, you'll receive regular updates about:</p>
        <ul>
            <li>Our ongoing programs and initiatives</li>
            <li>Impact stories from the communities we serve</li>
            <li>Upcoming events and volunteer opportunities</li>
            <li>Ways to get involved and support our mission</li>
        </ul>
        
        <p>We're committed to making a difference in the lives of young people, and your support helps us continue this important work.</p>
        
        <p>Visit our website to learn more about our programs:</p>
        <a href=\"https://anayd.org\" class=\"button\">Visit ANAYD.org</a>
    </div>
    
    <div class=\"social-links\">
        <p>Follow us on social media:</p>
        <a href=\"https://x.com/anayd_africa\">Twitter</a> | 
        <a href=\"https://www.instagram.com/anayd_africa/\">Instagram</a> | 
        <a href=\"https://www.linkedin.com/company/african-network-of-adolescents-and-young-persons-development-anayd/people/\">LinkedIn</a>
    </div>
    
    <div class=\"footer\">
        <p> 2025 African Network of Adolescents and Young Persons Development (ANAYD). All rights reserved.</p>
        <p>No. 39A Gyari Avenue GRA, Barnawa, Kaduna State, Nigeria</p>
        <p>If you no longer wish to receive these emails, you can <a href=\"https://anayd.org/backend/handlers/unsubscribe.php?email=" . urlencode($email) . "&token=" . urlencode(md5($email . 'anayd_unsubscribe_salt')) . "\">unsubscribe</a> at any time.</p>
    </div>
</body>
</html>";
    
    return [
        'subject' => $subject,
        'body' => $body
    ];
}
/**
 * Get the contact form confirmation email template
 * 
 * @param string $name The user's name
 * @param string $email The user's email address
 * @param string $subject The subject of their inquiry
 * @return array Array containing email subject and body
 */
function get_contact_confirmation_email($name, $email, $subject) {
    $subject_reply = "Thank you for contacting ANAYD: " . $subject;
    
    $body = "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Thank You for Contacting ANAYD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .social-links {
            text-align: center;
            margin-top: 15px;
        }
        .social-links a {
            margin: 0 10px;
            text-decoration: none;
        }
        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .important-links {
            margin-top: 20px;
        }
        .important-links a {
            display: inline-block;
            margin: 0 10px 10px 0;
            padding: 8px 15px;
            background-color: #f0f0f0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .important-links a:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <img src=\"https://anayd.org/asset/images/favicon.jpg\" alt=\"ANAYD Logo\" class=\"logo\">
        <h1>Thank You for Contacting Us!</h1>
    </div>
    
    <div class=\"content\">
        <p>Dear " . htmlspecialchars($name) . ",</p>
        
        <p>Thank you for reaching out to the African Network of Adolescents and Young Persons Development (ANAYD). We have received your inquiry regarding <strong>" . htmlspecialchars($subject) . "</strong>.</p>
        
        <p>Our team is reviewing your message and will get back to you as soon as possible. We are available 24/7 and aim to respond to all inquiries within 24 to 72 hours.</p>
        
        <div class=\"contact-info\">
            <h3>Our Contact Information:</h3>
            <p><strong>Email:</strong> info@anayd.org, anayd2017@gmail.com</p>
            <p><strong>Phone:</strong> +234(0)816-431-7505</p>
        </div>
        
        <div class=\"important-links\">
            <h3>Quick Links:</h3>
            <a href=\"https://anayd.org/volunteer.html\">Volunteer</a>
            <a href=\"https://anayd.org/partnership.html\">Partnership</a>
            <a href=\"https://anayd.org/donate.html\">Donate</a>
            <a href=\"https://anayd.org/projects.html\">Projects</a>
            <a href=\"https://anayd.org/annual-report.html\">Annual Report</a>
        </div>
    </div>
    
    <div class=\"social-links\">
        <p>Follow us on social media:</p>
        <a href=\"https://x.com/anayd_africa\">Twitter</a> | 
        <a href=\"https://www.instagram.com/anayd_africa/\">Instagram</a> | 
        <a href=\"https://www.linkedin.com/company/african-network-of-adolescents-and-young-persons-development-anayd/people/\">LinkedIn</a>
    </div>
    
    <div class=\"footer\">
        <p>© 2025 African Network of Adolescents and Young Persons Development (ANAYD). All rights reserved.</p>
        <p>No. 39A Gwari Avenue GRA, Barnawa, Kaduna State, Nigeria</p>
    </div>
</body>
</html>";
    
    return [
        'subject' => $subject_reply,
        'body' => $body
    ];
}
/**
 * Get the partnership confirmation email template
 * 
 * @param string $org_name The organization name
 * @param string $contact_person The contact person's name
 * @param string $email The contact email address
 * @param string $partnership_type The type of partnership
 * @return array Array containing email subject and body
 */
function get_partnership_confirmation_email($org_name, $contact_person, $email, $partnership_type) {
    $subject = "Thank you for your partnership interest with ANAYD";
    
    // Customize message based on partnership type
    $partnership_specific_content = "";
    
    switch ($partnership_type) {
        case 'financial':
            $partnership_specific_content = "<p>As a <strong>Financial Sponsor</strong>, your support will directly fund our programs that empower adolescents and young people across Africa. Your contribution will help us:</p>
            <ul>
                <li>Expand our reach to more communities</li>
                <li>Develop new educational resources</li>
                <li>Provide essential services to vulnerable youth</li>
                <li>Strengthen our organizational capacity</li>
            </ul>
            <p>Our team will contact you to discuss funding opportunities, impact reporting, and recognition options for your organization.</p>";
            break;
            
        case 'resource':
            $partnership_specific_content = "<p>As a <strong>Resource Donation Partner</strong>, your in-kind contributions will significantly enhance our capacity to serve. Your donations may include:</p>
            <ul>
                <li>Educational materials and equipment</li>
                <li>Technology resources</li>
                <li>Office supplies and infrastructure</li>
                <li>Professional services</li>
            </ul>
            <p>Our team will reach out to discuss your donation, logistics, and how we can acknowledge your generous support.</p>";
            break;
            
        case 'media':
            $partnership_specific_content = "<p>As a <strong>Media Support Partner</strong>, you'll help amplify our message and increase our visibility. Your partnership will assist us with:</p>
            <ul>
                <li>Raising awareness about youth development issues</li>
                <li>Promoting our programs and initiatives</li>
                <li>Sharing success stories and impact</li>
                <li>Reaching new audiences and supporters</li>
            </ul>
            <p>Our communications team will contact you to explore collaborative content, campaign opportunities, and media strategies.</p>";
            break;
            
        case 'skills':
            $partnership_specific_content = "<p>As a <strong>Skills/Training Partner</strong>, your expertise will empower our team and the youth we serve. Your contribution may include:</p>
            <ul>
                <li>Professional development workshops</li>
                <li>Mentorship programs</li>
                <li>Technical training for staff or beneficiaries</li>
                <li>Capacity building initiatives</li>
            </ul>
            <p>Our program team will reach out to discuss how your skills and training offerings can best support our mission.</p>";
            break;
            
        case 'event':
            $partnership_specific_content = "<p>As an <strong>Event Collaboration Partner</strong>, we'll work together to create meaningful experiences. This partnership may involve:</p>
            <ul>
                <li>Co-hosting awareness events</li>
                <li>Fundraising activities</li>
                <li>Youth engagement programs</li>
                <li>Community outreach initiatives</li>
            </ul>
            <p>Our events coordinator will contact you to discuss potential collaboration opportunities and event planning.</p>";
            break;
            
        default:
            $partnership_specific_content = "<p>We're excited about exploring this unique partnership opportunity with you. Our team will reach out to learn more about your proposal and how we can work together to advance our mission of empowering youth across Africa.</p>";
    }
    
    $body = "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Partnership Request Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: #3B82F6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .social-links {
            text-align: center;
            margin-top: 15px;
        }
        .social-links a {
            margin: 0 10px;
            text-decoration: none;
        }
        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .next-steps {
            margin-top: 20px;
            padding: 15px;
            background-color: #e6f7ff;
            border-radius: 5px;
            border-left: 4px solid #3B82F6;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <img src=\"https://anayd.org/asset/images/favicon.jpg\" alt=\"ANAYD Logo\" class=\"logo\">
        <h1>Partnership Request Received</h1>
    </div>
    
    <div class=\"content\">
        <p>Dear " . htmlspecialchars($contact_person) . ",</p>
        
        <p>Thank you for your interest in partnering with the African Network of Adolescents and Young Persons Development (ANAYD). We have received your partnership request from <strong>" . htmlspecialchars($org_name) . "</strong>.</p>
        
        " . $partnership_specific_content . "
        
        <div class=\"next-steps\">
            <h3>Next Steps:</h3>
            <ol>
                <li>Our partnership team will review your proposal within 3-5 business days</li>
                <li>You'll receive a follow-up email or call to discuss specifics</li>
                <li>We'll work together to develop a formal partnership agreement</li>
                <li>Implementation and impact measurement will begin</li>
            </ol>
        </div>
        
        <p>If you have any immediate questions or would like to provide additional information, please don't hesitate to contact us.</p>
        
        <div class=\"contact-info\">
            <h3>Our Partnership Team:</h3>
            <p><strong>Email:</strong> partnerships@anayd.org</p>
            <p><strong>Phone:</strong> +234(0)816-431-7505</p>
        </div>
    </div>
    
    <div class=\"social-links\">
        <p>Follow us on social media:</p>
        <a href=\"https://x.com/anayd_africa\">Twitter</a> | 
        <a href=\"https://www.instagram.com/anayd_africa/\">Instagram</a> | 
        <a href=\"https://www.linkedin.com/company/african-network-of-adolescents-and-young-persons-development-anayd/people/\">LinkedIn</a>
    </div>
    
    <div class=\"footer\">
        <p>© 2025 African Network of Adolescents and Young Persons Development (ANAYD). All rights reserved.</p>
        <p>No. 39A Gwari Avenue GRA, Barnawa, Kaduna State, Nigeria</p>
    </div>
</body>
</html>";
    
    return [
        'subject' => $subject,
        'body' => $body
    ];
}
?>
