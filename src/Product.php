<?php
class Product
{
    //DB require stuff
    private $conn;
    private $table = 'produits';

    //Properties for products
    public $nom;
    public $prix;
    public $quantite;
    public $description;
    public $productId;

    public function __construct(Database $connection)
    {
        $this->conn = $connection->connect();
    }

    // Display all available products
    public function getAll(): array
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->query($query);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
        return $data;
    }

    // Display a particular product
    public function get($productId): array | false
    {
        $query = "SELECT * FROM {$this->table} WHERE productId = :productId";
        $stmt = $this->conn->prepare($query);

        // bind parameters
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            return $data;
        }
        return false;
    }

    // search by name
    public function getByName($name): array | false
    {
        $query = "SELECT * FROM {$this->table} WHERE nom = :name";
        $stmt = $this->conn->prepare($query);

        //data 
        $this->nom = htmlspecialchars(strip_tags($name));

        // bind parameter
        $stmt->bindParam(':name', $this->nom, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            return $data;
        }
        return false;
    }

    // Create a new product
    public function create(array $data): string | false
    {
        $query = "INSERT INTO {$this->table} SET nom = :nom, prix = :prix, quantite = :quantite, description = :desc";
        $stmt = $this->conn->prepare($query);

        //data
        $this->nom = htmlspecialchars(strip_tags($data['nom']));
        $this->prix = htmlspecialchars(strip_tags($data['prix'] ?? 0));
        $this->quantite = htmlspecialchars(strip_tags($data['quantite'] ?? 0));
        $this->description = htmlspecialchars(strip_tags($data['description'] ?? ""));

        //Check if product already exists
        $check_query = "SELECT * FROM {$this->table} WHERE nom = :nom";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':nom', $this->nom, PDO::PARAM_STR);
        $check_stmt->execute();

        $product = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($product !== false) {
            // Product already exists
            return false;
        }
        
        //Bind data
        $stmt->bindParam(':nom', $this->nom, PDO::PARAM_STR);
        $stmt->bindParam(':prix', $this->prix, PDO::PARAM_INT);
        $stmt->bindParam(':quantite', $this->quantite, PDO::PARAM_INT);
        $stmt->bindParam(':desc', $this->description, PDO::PARAM_STR);

        //Execute statement
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    // Update a product
    public function update(array $current, array $new_data): int
    {
        $query = "UPDATE {$this->table} 
                  SET nom = :nom, prix = :prix, quantite = :quantite, description = :desc 
                  WHERE productId = :productId";
        $stmt = $this->conn->prepare($query);

        //data
        $this->nom = htmlspecialchars(strip_tags($new_data['nom'] ?? $current['nom']));
        $this->prix = htmlspecialchars(strip_tags($new_data['prix'] ?? $current['prix']));
        $this->quantite = htmlspecialchars(strip_tags($new_data['quantite'] ?? $current['quantite']));
        $this->description = htmlspecialchars(strip_tags($new_data['description'] ?? $current['description']));
        $this->productId = htmlspecialchars(strip_tags($current['productId']));

        //Bind data
        $stmt->bindParam(':nom', $this->nom, PDO::PARAM_STR);
        $stmt->bindParam(':prix', $this->prix, PDO::PARAM_INT);
        $stmt->bindParam(':quantite', $this->quantite, PDO::PARAM_INT);
        $stmt->bindParam(':desc', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':productId', $this->productId, PDO::PARAM_INT);

        //Execute statement
        $stmt->execute();

        return $stmt->rowCount();
    }

    // Delete a product
    public function delete($id): int
    {
        $query = "DELETE FROM {$this->table} WHERE productId = :productId";

        $this->productId = htmlspecialchars(strip_tags($id));

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':productId', $this->productId, PDO::PARAM_INT);

        //Execute statement
        $stmt->execute();

        return $stmt->rowCount();
    }
}
