<?php
require_once __DIR__ . './Database.php';


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

    public function __construct($connection)
    {
        $this->conn = $connection;
    }


    // Display all available products
    public function getAllProducts()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Display a particular product
    public function getProduct()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE productId = :productId LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['productId' => $this->productId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->nom = $row['nom'];
        $this->prix = $row['prix'];
        $this->quantite = $row['quantite'];
        $this->description = $row['description'];
        $this->productId = $row['productId'];
    }

    //Display similar products
    public function getProductSet()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE nom LIKE :nom';
        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags('*'.$this->nom.'*'));

        $stmt->bindParam(':nom', $this->nom);
        $stmt->execute();
        return $stmt;
    }

    // Create a new product
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . ' SET nom = :nom, prix = :prix, quantite = :quantite, description = :desc';
        $stmt = $this->conn->prepare($query);

        //data
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prix = htmlspecialchars(strip_tags($this->prix));
        $this->quantite = htmlspecialchars(strip_tags($this->quantite));
        $this->description = htmlspecialchars(strip_tags($this->description));

        //Bind data
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':quantite', $this->quantite);
        $stmt->bindParam(':desc', $this->description);

        //Execute statement
        if ($stmt->execute()) {
            return true;
        } else {
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    // Update a product
    public function update()
    {
        $query = 'UPDATE ' . $this->table . ' SET nom = :nom, prix = :prix, quantite = :quantite, description = :desc WHERE productId = :productId';
        $stmt = $this->conn->prepare($query);

        //data
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prix = htmlspecialchars(strip_tags($this->prix));
        $this->quantite = htmlspecialchars(strip_tags($this->quantite));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->productId = htmlspecialchars(strip_tags($this->productId));

        //Bind data
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prix', $this->prix);
        $stmt->bindParam(':quantite', $this->quantite);
        $stmt->bindParam(':desc', $this->description);
        $stmt->bindParam(':productId', $this->productId);

        //Execute statement
        if ($stmt->execute()) {
            return true;
        } else {
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    // Delete a product
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . 'WHERE productId = :productId';
        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));

        $stmt->bindParam(':productId', $this->productId);

        //Execute statement
        if ($stmt->execute()) {
            return true;
        } else {
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
}
