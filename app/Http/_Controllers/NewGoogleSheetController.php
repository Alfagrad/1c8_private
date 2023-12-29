<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use App\Http\Controllers\HomeController;


class NewGoogleSheetController extends Controller
{
    public static function index(){
    dd('стоп!');

        //email, которому надо дать доступ к файлу
        //alfastok-sheet@alfastok-262512.iam.gserviceaccount.com
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/GoogleSheet/my_secret.json');
        $client = new \Google_Client;
        try{
            $client->useApplicationDefaultCredentials();
            $client->setApplicationName("Alfastok Sheet");
            $client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
            if ($client->isAccessTokenExpired()) {
                $client->refreshTokenWithAssertion();
            }
            $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
            ServiceRequestFactory::setInstance(
                new DefaultServiceRequest($accessToken)
            );

            // Get our spreadsheet
            $spreadsheet = (new \Google\Spreadsheet\SpreadsheetService)
                ->getSpreadsheetFeed()
                ->getByTitle(setting('google_sheet_file_name')); // Имя файла на диске, к которому мы получаем доступ


            // Get the first worksheet (tab)
            $worksheets = $spreadsheet->getWorksheetFeed()->getEntries(); // Получаем список табов

            foreach ($worksheets as $key => $tab){
                if( $key == count($worksheets)-1 ){
                    break;
                }
                $tab->delete();
            }

            // foreach ($worksheets as $key => $tab){
            //     $tab->delete();
            // }
            $worksheet = $worksheets['0']; // Выбираем первый таб Лист 1
            //$listFeed = $worksheet->getListFeed();



//            $spreadsheet->addWorksheet('temp6734737', 10000,50); // Создаём временный таб с новой информацией

            $spreadsheet->addWorksheet('List temp'.str_random(), 10000,50); // Создаём временный таб с новой информацией

            $worksheets = $spreadsheet->getWorksheetFeed()->getEntries(); // Обновляем список всех табов

            $worksheet_temp = end($worksheets); // Получаем последний таб

            $cellFeed = $worksheet_temp->getCellFeed();

            $batchRequest = new \Google\Spreadsheet\Batch\BatchRequest();
            $batchRequest->addEntry($cellFeed->createCell(1, 1, "Код"));
            $batchRequest->addEntry($cellFeed->createCell(1, 2, "Категория"));
            $batchRequest->addEntry($cellFeed->createCell(1, 3, "Наименование товара"));
            $batchRequest->addEntry($cellFeed->createCell(1, 4, "Цена USD"));
            $batchRequest->addEntry($cellFeed->createCell(1, 5, "Цена BYN"));
            $batchRequest->addEntry($cellFeed->createCell(1, 6, "Розн USD"));
            $batchRequest->addEntry($cellFeed->createCell(1, 7, "Розн BYN"));
            $batchRequest->addEntry($cellFeed->createCell(1, 8, "Наличие"));
            $batchRequest->addEntry($cellFeed->createCell(1, 9, "Дата поступления"));
            $batchRequest->addEntry($cellFeed->createCell(1, 10, "Сообщение i"));
            $batchRequest->addEntry($cellFeed->createCell(1, 11, "Сообщение %"));

            $batchRequest->addEntry($cellFeed->createCell(1, 12, "Комплектация"));
            $batchRequest->addEntry($cellFeed->createCell(1, 13, "Характеристики"));
            $batchRequest->addEntry($cellFeed->createCell(1, 14, "Преимущества"));
            $batchRequest->addEntry($cellFeed->createCell(1, 15, "Производитель"));
            $batchRequest->addEntry($cellFeed->createCell(1, 16, "Назначение"));
            $batchRequest->addEntry($cellFeed->createCell(1, 17, "Срок годности"));
            $batchRequest->addEntry($cellFeed->createCell(1, 18, "Страна изготовления"));
            $batchRequest->addEntry($cellFeed->createCell(1, 19, "Бренд"));
            $batchRequest->addEntry($cellFeed->createCell(1, 20, "Импортер"));
            $batchRequest->addEntry($cellFeed->createCell(1, 21, "Штрих-код"));
            $batchRequest->addEntry($cellFeed->createCell(1, 22, "Сертификат"));
            $batchRequest->addEntry($cellFeed->createCell(1, 23, "Габариты"));
            $batchRequest->addEntry($cellFeed->createCell(1, 24, "Вес с упаковкой"));
            $batchRequest->addEntry($cellFeed->createCell(1, 25, "Артикул"));
            $batchRequest->addEntry($cellFeed->createCell(1, 26, "Гарантийный срок (месяцев)"));
            $batchRequest->addEntry($cellFeed->createCell(1, 27, "Новинка"));

            $batchRequest->addEntry($cellFeed->createCell(1, 28, '=hyperlink("http://alfastok.by/shopsvec";"ОБНОВИТЬ КАТАЛОГ")'));


            $row_num = 2;
            foreach (HomeController::generatePriceVek() as $row){
                $col = 1;
                foreach ($row as $key => $val){
                    $batchRequest->addEntry($cellFeed->createCell($row_num, $col, $val));
                    ++$col;
                }

                ++$row_num;

                if ($row_num == 1350) {
                    break;
                }
            }

            $batchResponse = $cellFeed->insertBatch($batchRequest);
// dd($batchResponse);
            // Удаляем все вкладки кроме той, которую добавили
            foreach ($worksheets as $key => $tab){
                if( $key == count($worksheets)-1 ){
                    break;
                }
                $tab->delete();
            }

            $worksheet = end($worksheets);

            // даем имя листу
            $name = date('d.m.Y H:i');
            $worksheet->update($name);


        }catch(Exception $e){
            echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
        }


        //$price = HomeController::generatePrice();

        //dd($price);


        //dd(__DIR__ . '\GoogleSheet\my_secret2.json');

        //$client = $this->getClient();


        /*$client_id = '471706587070-cq707nmkuc0uospaf2boc2l118at9q03.apps.googleusercontent.com';
        $client_secret = 'Ttbe4rAnFJE5MyAQvBvbFbO_';

        $client = new \Google_Client();
        $client->setAccessToken('AIzaSyC_rTrT7rpOeFzrvLg1LTcksGONhkwWIRE');

        $service = new \Google_Service_Sheets($client);
        $service->spreadsheets_sheets
        dd($service);*/
        echo "Прайс-лист ".setting('google_sheet_file_name')." обновлен!";
    }

