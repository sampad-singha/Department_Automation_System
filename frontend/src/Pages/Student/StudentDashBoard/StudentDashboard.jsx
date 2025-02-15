import { useContext, useState, useEffect } from 'react';
import { AuthContext } from '../../../Layout/AuthProvider/AuthProvider';
import { NavLink } from 'react-router-dom';
import { FiMenu, FiX, FiBook, FiCalendar, FiBell, FiUser } from 'react-icons/fi';
import { IoIosLogIn, IoIosLogOut } from 'react-icons/io';

const StudentDashboard = () => {
    const { user, logOut } = useContext(AuthContext);
    const [menuOpen, setMenuOpen] = useState(false);
    const [studentData, setStudentData] = useState(null);

    useEffect(() => {
        // Mock API call to fetch student data
        setTimeout(() => {
            setStudentData({
                name: "John Doe",
                rollNo: "123456",
                department: "Computer Science",
                coursesEnrolled: 5,
                semester: 4,
                gpa: 3.8,
                upcomingDeadlines: [
                    { id: 1, title: "Assignment Due", date: "Feb 20" },
                    { id: 2, title: "Project Submission", date: "Feb 25" }
                ],
                notifications: [
                    "New assignment uploaded",
                    "Mid-term exam schedule released"
                ]
            });
        }, 1000);
    }, []);

    const handleSignOut = () => {
        logOut().catch(err => console.error(err));
    };

    return (
        <div className="flex min-h-screen bg-gray-100">
            {/* Sidebar */}
            <aside className="bg-gray-900 text-white w-64 p-6 fixed h-full top-0 left-0 shadow-lg">
                <h2 className="text-2xl font-bold mb-6">Dashboard</h2>
                <nav className="flex flex-col gap-4">
                    <NavLink to="/courses" className="hover:text-primary">Courses</NavLink>
                    <NavLink to="/timetable" className="hover:text-primary">Timetable</NavLink>
                    <NavLink to="/grades" className="hover:text-primary">Grades</NavLink>
                    <NavLink to="/library" className="hover:text-primary">Library</NavLink>
                    {user ? (
                        <button onClick={handleSignOut} className="text-red-400">Logout</button>
                    ) : (
                        <NavLink to="/login" className="hover:text-primary">Login</NavLink>
                    )}
                </nav>
            </aside>

            {/* Dashboard Content */}
            <div className="flex-1 p-6 ml-64">
                {studentData ? (
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {/* Profile Section */}
                        <div className="bg-white p-6 rounded-lg shadow-md">
                            <h2 className="text-xl font-semibold">Profile</h2>
                            <p><strong>Name:</strong> {studentData.name}</p>
                            <p><strong>Roll No:</strong> {studentData.rollNo}</p>
                            <p><strong>Department:</strong> {studentData.department}</p>
                        </div>
                        {/* Stats Section */}
                        <div className="bg-white p-6 rounded-lg shadow-md">
                            <h2 className="text-xl font-semibold">Quick Stats</h2>
                            <p><FiBook className="inline-block mr-2" /> Courses Enrolled: {studentData.coursesEnrolled}</p>
                            <p><FiUser className="inline-block mr-2" /> Semester: {studentData.semester}</p>
                            <p><FiCalendar className="inline-block mr-2" /> GPA: {studentData.gpa}</p>
                        </div>
                        {/* Notifications */}
                        <div className="bg-white p-6 rounded-lg shadow-md">
                            <h2 className="text-xl font-semibold">Notifications</h2>
                            <ul>
                                {studentData.notifications.map((note, index) => (
                                    <li key={index} className="text-sm text-gray-600">{note}</li>
                                ))}
                            </ul>
                        </div>
                    </div>
                ) : (
                    <p>Loading student data...</p>
                )}
            </div>
        </div>
    );
};

export default StudentDashboard;
