import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from '../src/Pages/AllUser/HomePage/Home.jsx';
import Login from '../src/Pages/AllUser/Login/Login.jsx';
import StudentDashboard from '../src/Pages/Student/StudentDashBoard/StudentDashboard.jsx';
import TeacherDashboard from '../src/Pages/Teacher/TeacherDashBoard/TeacherDashboard.jsx';
import PrivateRoute from '../src/Component/PrivateRoute.jsx';
import { AuthProvider } from './Contexts/AuthContext.jsx';
import MainLayout from './layouts/MainLayout.jsx';
import ForgotPassword from "./Pages/AllUser/Login/ForgotPassword.jsx";
import ResetPassword from "./Pages/AllUser/Login/ResetPassword.jsx";
import EnrolledCourses from './Pages/Student/Course/EnrolledCourses.jsx';
import CoursesList from "./Pages/AllUser/Course/CourseList.jsx";
import TeacherCourses from "./Pages/Teacher/Course/TeacherCourses.jsx";
import TeacherCourseDetails from "./Pages/Teacher/Course/TeacherCourseDetails.jsx";
import GradeAssignments from "./Pages/Teacher/Course/GradeAssignments.jsx";

function App() {
    return (
        <Router>
            <AuthProvider>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/login" element={<Login />} />
                    <Route path="/forgot-password" element={<ForgotPassword />} />
                    <Route path="/reset-password" element={<ResetPassword />} />

                    {/* Routes with Sidebar */}
                    <Route element={<PrivateRoute><MainLayout /></PrivateRoute>}>
                        <Route path="/student/dashboard" element={<StudentDashboard />} />
                        <Route path="/student/courses/all" element={<CoursesList />} />
                        <Route path="/student/courses/enrolled" element={<EnrolledCourses />} />
                        <Route path="/teacher/dashboard" element={<TeacherDashboard />} />
                        <Route path="/teacher/courses/my-courses" element={<TeacherCourses />} />
                        <Route path={`/teacher/courses/my-courses/:courseSessionId`} element={<TeacherCourseDetails />} />
                        <Route path="/grade-assignments/:courseSessionId" element={<GradeAssignments />} />
                    </Route>

                </Routes>
            </AuthProvider>
        </Router>
    );
}

export default App;
