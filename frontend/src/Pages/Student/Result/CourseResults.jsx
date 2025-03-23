import { useEffect, useState } from "react";
import jsPDF from "jspdf";
import "jspdf-autotable"; // Plugin for creating tables in PDF
import api from "../../../api";

const CourseResults = () => {
    const [courses, setCourses] = useState([]);
    const [year, setYear] = useState("");
    const [semester, setSemester] = useState("");
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [cgpa, setCgpa] = useState(null); // State for CGPA

    // Fetch course results based on year and semester
    const fetchResults = async () => {
        if (!year || !semester) {
            setError("Please enter both Year and Semester.");
            return;
        }

        setLoading(true);
        setError(null);

        try {
            const response = await api.get(
                `/result/show-full-result/${year}/${semester}`,
                {
                    headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
                }
            );

            if (response.data.courses && response.data.courses.length > 0) {
                setCourses(response.data.courses);
                setCgpa(response.data.total_cgpa); // Set CGPA
            } else {
                setError("No results found for the selected year and semester.");
                setCourses([]);
                setCgpa(null);
            }
        } catch (error) {
            console.error("Error fetching course results:", error);
            setError("Failed to load course results. Please try again later.");
            setCourses([]);
            setCgpa(null);
        } finally {
            setLoading(false);
        }
    };

    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();
        fetchResults();
    };

    // Generate and download PDF
    const downloadPDF = () => {
        if (courses.length === 0) {
            alert("No course results available to generate PDF.");
            return;
        }

        const doc = new jsPDF();

        // Add title
        doc.setFontSize(18);
        doc.text("Course Results", 14, 22);

        // Add CGPA
        doc.setFontSize(12);
        doc.text(`Semester CGPA: ${cgpa?.toFixed(2)}`, 14, 30);

        // Prepare table data
        const tableData = courses.map((course) => [
            course.course_name,
            course.year,
            course.semester,
            course.max_final_term_marks,
            course.grade,
            course.gpa,
            course.remark,
            course.credit_hours,
        ]);

        // Add table
        doc.autoTable({
            startY: 40,
            head: [["Course Name", "Year", "Semester", "Final Marks", "Grade", "GPA", "Remark", "Credit Hours"]],
            body: tableData,
            theme: "grid", // Add grid lines
            styles: { fontSize: 10 }, // Set font size
            headStyles: { fillColor: [41, 128, 185] }, // Header background color
        });

        // Save the PDF
        doc.save(`course-results-${year}-${semester}.pdf`);
    };

    return (
        <div className="max-w-4xl p-6 mx-auto bg-white border rounded-lg shadow-lg">
            <h2 className="mb-6 text-2xl font-bold text-center">Course Results</h2>

            {/* Input Form */}
            <form onSubmit={handleSubmit} className="flex flex-col items-center gap-4 mb-6">
                <div className="flex flex-col w-full gap-4 sm:flex-row sm:w-auto">
                    <input
                        type="number"
                        placeholder="Enter Year"
                        value={year}
                        onChange={(e) => setYear(e.target.value)}
                        className="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        min="1"
                        required
                    />
                    <input
                        type="number"
                        placeholder="Enter Semester"
                        value={semester}
                        onChange={(e) => setSemester(e.target.value)}
                        className="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        min="1"
                        required
                    />
                </div>
                <button
                    type="submit"
                    disabled={loading}
                    className="px-6 py-2 font-bold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-blue-300"
                >
                    {loading ? "Fetching..." : "Fetch Results"}
                </button>
            </form>

            {/* Error Message */}
            {error && <p className="mb-4 text-center text-red-500">{error}</p>}

            {/* Loading State */}
            {loading && <p className="mb-4 text-center">Loading results...</p>}

            {/* Results Table and PDF Download Button */}
            {courses.length > 0 && (
                <>
                    <div className="mb-6 overflow-x-auto">
                        <table className="w-full text-center border border-collapse border-gray-300">
                            <thead className="bg-gray-200">
                                <tr>
                                    <th className="px-4 py-2 border border-gray-300">Course Name</th>
                                    <th className="px-4 py-2 border border-gray-300">Year</th>
                                    <th className="px-4 py-2 border border-gray-300">Semester</th>
                                    <th className="px-4 py-2 border border-gray-300">Final Marks</th>
                                    <th className="px-4 py-2 border border-gray-300">Grade</th>
                                    <th className="px-4 py-2 border border-gray-300">GPA</th>
                                    <th className="px-4 py-2 border border-gray-300">Remark</th>
                                    <th className="px-4 py-2 border border-gray-300">Credit Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                {courses.map((course) => (
                                    <tr key={course.course_id} className="hover:bg-gray-50">
                                        <td className="px-4 py-2 border border-gray-300">{course.course_name}</td>
                                        <td className="px-4 py-2 border border-gray-300">{course.year}</td>
                                        <td className="px-4 py-2 border border-gray-300">{course.semester}</td>
                                        <td className="px-4 py-2 border border-gray-300">{course.max_final_term_marks}</td>
                                        <td
                                            className={`px-4 py-2 border border-gray-300 font-bold ${
                                                course.grade === "F" ? "text-red-600" : "text-green-600"
                                            }`}
                                        >
                                            {course.grade}
                                        </td>
                                        <td className="px-4 py-2 border border-gray-300">{course.gpa}</td>
                                        <td
                                            className={`px-4 py-2 border border-gray-300 font-semibold ${
                                                course.remark === "Fail" ? "text-red-500" : "text-green-500"
                                            }`}
                                        >
                                            {course.remark}
                                        </td>
                                        <td className="px-4 py-2 border border-gray-300">{course.credit_hours}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Display CGPA */}
                    <div className="mb-4 text-center">
                        <p className="text-lg font-semibold">
                            Semester CGPA: <span className="text-blue-600">{cgpa?.toFixed(2)}</span>
                        </p>
                    </div>

                    {/* PDF Download Button */}
                    <div className="flex justify-center">
                        <button
                            onClick={downloadPDF}
                            className="px-6 py-2 font-bold text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            Download PDF
                        </button>
                    </div>
                </>
            )}

            {/* Show No Data Message */}
            {courses.length === 0 && !loading && !error && (
                <p className="text-center">No course results available.</p>
            )}
        </div>
    );
};

export default CourseResults;