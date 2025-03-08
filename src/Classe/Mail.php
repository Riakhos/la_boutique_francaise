<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
	public function send($to_email, $to_name, $subject, $template, $vars = null)
	{
        //Récupération du template
        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template);

        // Récupération d es variables facultatives
        if ($vars) {
            foreach ($vars as $key => $var) {
                str_replace('{'.$key.'}', $var, $content);
            }
        }
        
		$mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'],true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "richard.bonnegent@gmail.com",
                        'Name' => "La boutique Française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
					'TemplateID' => 6789821,
					"TemplateLanguage" => true,
                    'Subject' => $subject,
                    'Variables' => [
						'content' => $content
					]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);

	}
}