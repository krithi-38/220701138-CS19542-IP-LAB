import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/StudentDetailsServlet")
public class StudentDetailsServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        // Database connection details
        String jdbcURL = "jdbc:mysql://localhost:3306/StudentDB";
        String jdbcUsername = "root";
        String jdbcPassword = "password";

        // Get the registration number from the request
        String regNo = request.getParameter("reg_no");

        // Prepare the response
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        if (regNo == null || regNo.isEmpty()) {
            out.println("Invalid registration number.");
            return;
        }

        try {
            // Connect to the database
            Class.forName("com.mysql.cj.jdbc.Driver");
            Connection conn = DriverManager.getConnection(jdbcURL, jdbcUsername, jdbcPassword);

            // Query to get student details
            String query = "SELECT * FROM Students WHERE reg_no = ?";
            PreparedStatement stmt = conn.prepareStatement(query);
            stmt.setString(1, regNo);

            // Execute query
            ResultSet rs = stmt.executeQuery();

            // Display results
            if (rs.next()) {
                out.println("<h3>Student Details</h3>");
                out.println("<p><strong>Registration Number:</strong> " + rs.getString("reg_no") + "</p>");
                out.println("<p><strong>Name:</strong> " + rs.getString("name") + "</p>");
                out.println("<p><strong>Age:</strong> " + rs.getInt("age") + "</p>");
                out.println("<p><strong>Course:</strong> " + rs.getString("course") + "</p>");
            } else {
                out.println("<p>No details found for registration number: " + regNo + "</p>");
            }

            // Close connections
            rs.close();
            stmt.close();
            conn.close();
        } catch (Exception e) {
            e.printStackTrace();
            out.println("<p>Error retrieving student details.</p>");
        }
    }
}
