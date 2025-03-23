import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from '../src/Pages/AllUser/HomePage/Home.jsx';
import Login from '../src/Pages/AllUser/Login/Login.jsx';
import StudentDashboard from '../src/Pages/Student/StudentDashBoard/StudentDashboard.jsx';
import TeacherDashboard from '../src/Pages/Teacher/TeacherDashBoard/TeacherDashboard.jsx';
import PrivateRoute from '../src/Component/PrivateRoute.jsx';
import { AuthProvider } from './Contexts/AuthContext.jsx';
import MainLayout from './layouts/MainLayout.jsx';
import Notices from './Pages/Student/Notice/Notices .jsx';
import NoticeDetails from './Pages/Student/Notice/NoticeDetails.jsx';
import CourseResults from './Pages/Student/Result/CourseResults.jsx';

function App() {
    return (
        <Router>
            <AuthProvider>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/login" element={<Login />} />

                    {/* Routes with Sidebar */}
                    <Route element={<PrivateRoute><MainLayout /></PrivateRoute>}>
                        <Route path="/student/dashboard" element={<StudentDashboard />} />
                        <Route path="/teacher/dashboard" element={<TeacherDashboard />} />
                        <Route path="/student/notices" element={<Notices />} />
                        <Route path="/notices/:id" element={<NoticeDetails />} />
                        <Route path="/student/results" element={<CourseResults />} />
                    </Route>
                </Routes>
            </AuthProvider>
        </Router>
    );
}

export default App;
