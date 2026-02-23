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
$string['pluginname'] = 'Поставщик API Yandex AI';
$string['privacy:metadata:aiprovider_yandexai:prompttext'] = 'Введенная пользователем текстовая подсказка, используемая при создании ответа.';
$string['privacy:metadata:aiprovider_yandexai:model'] = 'Модель, используемая для создания ответа.';
$string['privacy:metadata:aiprovider_yandexai:numberimages'] = 'Количество изображений, используемых в ответе при создании изображений.';
$string['privacy:metadata:aiprovider_yandexai:responseformat'] = 'Формат ответа при создании изображений.';
$string['privacy:metadata:aiprovider_yandexai:externalpurpose'] = 'Эта информация отправляется в API YandexGPT для создания ответа. 
Чтобы повышать качество генерируемых ответов, API Foundation Models логирует промты пользователей. 
Не передавайте в запросах чувствительную информацию и персональные данные. 
Настройки вашей учетной записи YandexGPT могут изменить способ хранения и запоминание этих данных в YandexGPT. 
Этим плагином никакие пользовательские данные явно не отправляются в YandexGPT и не сохраняются в LMS Moodle.';
$string['success'] = 'Успех';
$string['action:systeminstruction'] = 'Системная инструкция';
$string['action:systeminstruction_help'] = 'Эта инструкция отправляется в модель ИИ вместе с подсказкой пользователя. Редактирование этой инструкции не рекомендуется, если только это не является абсолютно необходимым.';
$string['apikey'] = 'Ключ API YandexAI';
$string['apikey_help'] = 'О том, как получить ключ, Вы можете узнать <a href="https://yandex.cloud/ru/docs/foundation-models/api-ref/authentication" target="_blank">здесь</a>.';
$string['endpoint'] = 'Конечная точка API';
$string['temperature'] = 'Температура генерации';
$string['temperature_help'] = 'Введите значение от 0 до 1 с точностью до 2-х знаков';

// Strings for the text generation action.
$string['action:generate_text:model'] = 'Модель ИИ';
$string['action:generate_text:model_help'] = 'Модель, используемая для создания текстового ответа. Необходимо заменить catalogue_id на ID каталога, который вы хотите использовать. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Справка</a> по получению идентификатора каталога';
$string['action:generate_text:allowhtml'] = 'Использовать html ответы';

// Strings for the image generation action.
$string['action:generate_image:model'] = 'Модель ИИ';
$string['action:generate_image:model_help'] = 'Модель, используемая для генерации изображения. Необходимо заменить catalogue_id на ID каталога, который вы хотите использовать. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Справка</a> по получению идентификатора каталога';
$string['action:generate_image:getimageurl'] = 'Ссылка на получение результата генерации';
$string['action:generate_image:width'] = 'Соотношение сторон генерируемого изображения: ширина';
$string['action:generate_image:height'] = 'Соотношение сторон генерируемого изображения: высота';

// Strings for the "Summarize text" action.
$string['action:summarise_text:model'] = 'Модель ИИ';
$string['action:summarise_text:model_help'] = 'Модель, используемая для краткого изложения текста. Необходимо заменить catalogue_id на ID каталога, который вы хотите использовать. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Справка</a> по получению идентификатора каталога';

// Strings for the "Explain text" action.
$string['action:explain_text:model'] = 'Модель ИИ';
$string['action:explain_text:model_help'] = 'Модель, используемая для пояснения текста. Необходимо заменить catalogue_id на ID каталога, который вы хотите использовать. <a href="https://yandex.cloud/ru/docs/resource-manager/operations/folder/get-id" target="_blank">Справка</a> по получению идентификатора каталога';
