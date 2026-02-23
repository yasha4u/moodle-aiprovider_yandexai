<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   aiprovider_yandexai
 * @copyright 2024 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General plugin strings.
$string['pluginname'] = 'Yandex AI API Provider';
$string['privacy:metadata:aiprovider_yandexai:prompttext'] = 'The user entered text prompt used to generate the response.';
$string['privacy:metadata:aiprovider_yandexai:model'] = 'The model used to generate the response.';
$string['privacy:metadata:aiprovider_yandexai:numberimages'] = 'The number of images used in the response. When generating images.';
$string['privacy:metadata:aiprovider_yandexai:responseformat'] = 'The format of the response. When generating images.';
$string['privacy:metadata:aiprovider_yandexai:externalpurpose'] = 'This information is sent to the YandexGPT API to create a response. 
To improve the quality of the responses generated, the Foundation Models API logs user accounts. 
Do not transmit sensitive information and personal data in requests. 
Your YandexGPT account settings may change the way YandexGPT stores and remembers this data. 
With this plugin, no user data is explicitly sent to YandexGPT and is not saved in LMS Moodle.';
$string['success'] = 'Success';
$string['action:systeminstruction'] = 'System instruction';
$string['action:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['apikey'] = 'YandexAI API Key';
$string['apikey_help'] = 'You can find out how to get the key <a href="https://yandex.cloud/ru/docs/foundation-models/api-ref/authentication" target="_blank">here</a>.';
$string['endpoint'] = 'API endpoint URL';
$string['temperature'] = 'Generation temperature';
$string['temperature_help'] = 'Enter a value from 0 to 1 with an accuracy of 2 characters';

// Strings for the text generation action.
$string['action:generate_text:model'] = 'AI model address';
$string['action:generate_text:model_help'] = 'The model used to create a text response. Replace the catalogue_id with the ID of the folder you want to use. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Help</a> for getting the folder ID';
$string['action:generate_text:allowhtml'] = 'Use html responses';

// Strings for the image generation action.
$string['action:generate_image:model'] = 'AI model address';
$string['action:generate_image:model_help'] = 'The model used to generate the image. Replace the catalogue_id with the ID of the folder you want to use. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Help</a> for getting the folder ID';
$string['action:generate_image:getimageurl'] = 'Link to get the result of the generation';
$string['action:generate_image:width'] = 'Aspect ratio of the generated image: width';
$string['action:generate_image:height'] = 'Aspect ratio of the generated image: height';

// Strings for the "Summarize text" action.
$string['action:summarise_text:model'] = 'AI model';
$string['action:summarise_text:model_help'] = 'The model used to summarize the text. Replace the catalogue_id with the ID of the folder you want to use. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Help</a> for getting the folder ID';

// Strings for the "Explain text" action.
$string['action:explain_text:model'] = 'AI model';
$string['action:explain_text:model_help'] = 'The model used to explain the text. Replace the catalogue_id with the ID of the folder you want to use. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Help</a> for getting the folder ID';
