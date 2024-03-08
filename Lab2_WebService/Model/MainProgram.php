<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class MainProgram implements DbHandler
{
    private $_capsule;
    
    public function __construct()
    {
        $this->_capsule = new Capsule;
    }
    
    public function connect()
    {
        try 
        {
            $this->_capsule->addConnection([
                "driver" => database_type,
                "host" => host_name,
                "database" => database_name,
                "username" => user_name,
                "password" => user_password
            ]);
            $this->_capsule->setAsGlobal();
            $this->_capsule->bootEloquent();
            return true;
        } catch (\Exception $ex) {
            echo "Error : " . $ex->getMessage();
            return false;
        }
    }
    
    public function getData($fields = array(), $start = 0)
    {
        $items = Items::skip($start)->take(5)->get();
        if (empty($fields)) 
        {
            return $items;
        } else {
            $result = [];
            foreach ($items as $item) {
                $data = [];
                foreach ($fields as $field) {
                    $data[$field] = $item->$field;
                }
                $result[] = $data;
            }
            return $result;
        }
    }
    
    public function disconnect()
    {
        try 
        {
            Capsule::disconnect();
            return true;
        } catch (\Exception $e) {
            echo "Error : " . $e->getMessage();
            return false;
        }
    }
    
    public function getRecordById($id,$primary_key)
    {
        $item = Items::where($primary_key, "=", $id)->get();
        if (count($item) > 0)
            return $item[0];
    }
    
    public function searchByColumn($field, $value)
    {
        $items = Items::where($field, 'like', "%$value%")->get();

        if ($items->isNotEmpty()) {
            return $items;
        }
    }

    public function insertItem($formData)
    {
        try {
            $result = Items::insert($formData);
            // print_r($formData);

            if ($result) {
                echo "<h4>Data added successfully :)</h4>";
            } else {
                echo "Failed to add item";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function checkExistingID($id) 
    {
        $existingRecord = Items::where('id', $id)->first();
        return $existingRecord !== null;
    }



    /*********************************** API ***********************************/

    public function handleRequest($method)
    {
        switch ($method) {
            case 'GET':
                $this->handleGET();
                break;
            case 'POST':
                $this->handlePOST();
                break;
            default:
                $this->sendResponse(405, ['error' => 'Method not allowed!']);
        }
    }

    private function handleGET()
    {
        if (!empty($_GET['id'])) 
        {
            $itemId = $_GET['id'];

            if (!$this->checkExistingID($itemId)) 
            {
                $this->sendResponse(404, ['error' => 'Resource does not exist']);
            }

            $item = $this->getRecordById($itemId, 'id');
            $this->sendResponse(200, $item->toArray());

        } else {
            $urlParts = parse_url($_SERVER['REQUEST_URI']);
            $urlPath = trim($urlParts['path'], '/');
            $urlSegments = explode('/', $urlPath);

            if (count($urlSegments) >= 4 && $urlSegments[2] === 'items') 
            {
                $itemId = end($urlSegments);

                if (!$this->checkExistingID($itemId)) 
                {
                    $this->sendResponse(404, ['error' => 'Resource does not exist']);
                }

                $item = $this->getRecordById($itemId, 'id');
                $this->sendResponse(200, $item->toArray());

            } else {
                $this->sendResponse(400, ['error' => 'Invalid URL format']);
            }
        }
    }

    

    private function handlePOST()
    {
        $formData = json_decode(file_get_contents('php://input'), true);

        if (!$formData) {
            $this->sendResponse(400, ['error' => 'Invalid request body']);
        }

        $requiredFields = ['id', 'PRODUCT_code', 'product_name', 'list_price', 'reorder_level', 'Units_In_Stock', 'CouNtry', 'Rating', 'date', 'discontinued', 'category'];

        foreach ($requiredFields as $field) {
            if (!isset($formData[$field])) {
                $this->sendResponse(400, ['error' => "Field '$field' is missing"]);
            }
        }

        if ($this->checkExistingID($formData['id'])) {
            $this->sendResponse(400, ['error' => 'Duplicate ID found']);
        }

        $this->insertItem($formData);

        $this->sendResponse(201, ['message' => 'Item added successfully']);
    }

    private function sendResponse($statusCode, $data)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }


}
?>
