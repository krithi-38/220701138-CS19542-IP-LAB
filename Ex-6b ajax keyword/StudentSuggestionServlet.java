import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/StudentSuggestionServlet")
public class StudentSuggestionServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    // Sample student names
    private static final String[] STUDENTS = {
        "Alice", "Bob", "Charlie", "David", "Eve",
        "Frank", "Grace", "Hannah", "Ivy", "Jack",
        "Kathy", "Liam", "Mia", "Nathan", "Olivia",
        "Paul", "Quincy", "Rachel", "Sophia", "Tom"
    };

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        // Set response content type
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        // Get the query parameter
        String query = request.getParameter("query");

        // Check if query is not null and generate suggestions
        if (query != null && !query.isEmpty()) {
            for (String student : STUDENTS) {
                if (student.toLowerCase().startsWith(query.toLowerCase())) {
                    out.println("<div onclick=\"fillInput('" + student + "')\">" + student + "</div>");
                }
            }
        }
    }
}
