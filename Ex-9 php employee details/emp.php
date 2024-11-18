<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "CompanyDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch employee details
function getEmployeeDetails($conn) {
    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);
    return $result;
}

// Function to update employee details
function updateEmployeeDetails($conn, $empID, $name, $designation, $salary, $doj) {
    $stmt = $conn->prepare("UPDATE employees SET Name=?, Designation=?, Salary=?, DOJ=? WHERE EmpID=?");
    $stmt->bind_param("sssdi", $name, $designation, $salary, $doj, $empID);
    return $stmt->execute();
}

// Handle form submission for updating employee details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $empID = $_POST['EmpID'];
    $name = $_POST['Name'];
    $designation = $_POST['Designation'];
    $salary = $_POST['Salary'];
    $doj = $_POST['DOJ'];

    if (updateEmployeeDetails($conn, $empID, $name, $designation, $salary, $doj)) {
        echo "Employee details updated successfully!";
    } else {
        echo "Error updating employee details.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        form {
            margin-top: 20px;
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Employee Details</h1>

    <!-- Display Employee Details -->
    <h2>Employee List</h2>
    <table>
        <tr>
            <th>EmpID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Salary</th>
            <th>DOJ</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = getEmployeeDetails($conn);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["EmpID"] . "</td>";
                echo "<td>" . $row["Name"] . "</td>";
                echo "<td>" . $row["Designation"] . "</td>";
                echo "<td>" . $row["Salary"] . "</td>";
                echo "<td>" . $row["DOJ"] . "</td>";
                echo "<td><a href='employee.php?edit=" . $row["EmpID"] . "'>Edit</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No employees found</td></tr>";
        }
        ?>
    </table>

    <!-- Form to Update Employee Details -->
    <?php
    if (isset($_GET['edit'])) {
        $empID = $_GET['edit'];
        $result = $conn->query("SELECT * FROM employees WHERE EmpID = $empID");
        $employee = $result->fetch_assoc();
    ?>
        <h2>Edit Employee Details</h2>
        <form action="employee.php" method="post">
            <input type="hidden" name="EmpID" value="<?php echo $employee['EmpID']; ?>">
            <label for="Name">Name:</label>
            <input type="text" id="Name" name="Name" value="<?php echo $employee['Name']; ?>" required>
            
            <label for="Designation">Designation:</label>
            <input type="text" id="Designation" name="Designation" value="<?php echo $employee['Designation']; ?>" required>
            
            <label for="Salary">Salary:</label>
            <input type="number" id="Salary" name="Salary" value="<?php echo $employee['Salary']; ?>" required>
            
            <label for="DOJ">Date of Joining:</label>
            <input type="date" id="DOJ" name="DOJ" value="<?php echo $employee['DOJ']; ?>" required>
            
            <button type="submit" name="update">Update</button>
        </form>
    <?php
    }
    ?>

</body>
</html>

<?php
$conn->close();
?>
