import {
    createBrowserRouter,
} from "react-router-dom";
import ErrorPage from "../Pages/ErrorPage/ErrorPage";
import Root from "./Root";
import Home from "../Pages/AllUser/HomePage/Home";
import Register from "../Pages/AllUser/Register/Register";
import Login from "../Pages/AllUser/Login/Login";
import About from "../Pages/AllUser/About/About";
import PrivateRoute from "./PrivateRoute/PrivateRoute";
import StudentDashBoard from "./StudentDashBoard/StudentDashBoard";
import Message from "../Pages/student/Messages/Message";
import StudentDashBoardHome from "../Pages/student/StudentDashBoardHome/StudentDashBoardHome";
import Result from "../Pages/student/Result/Result";
import ClassSchedule from "../Pages/student/ClassSchedule/ClassSchedule";

const Router = createBrowserRouter([
    {
        path: "/",
        element: <Root />,  // Main layout component
        errorElement: <ErrorPage />,
        children: [
            {
                path: "/",
                element: <Home />,
            },
            {
                path: "/register",
                element: <Register />,
            },
            {
                path: "/login",
                element: <Login />,
            },
            {
                path: "/about",
                element: <PrivateRoute><About /></PrivateRoute>,
            },
            // {
            //     path: "/messages",
            //     element: <Message></Message>
            // },
        ],
    },
    {
        path: "/studentDashBoard",
// layout
        element: <PrivateRoute><StudentDashBoard /></PrivateRoute>,
        errorElement: <ErrorPage></ErrorPage>,
        children: [
            {
                path: "/studentDashBoard",
                element: <StudentDashBoardHome />,
            },
            {
                path: "messages", // Change this from "/messages" to "messages"
                element: <Message></Message>
            },
            {
                path: "result", // Change this from "/messages" to "messages"
                element: <Result></Result>
            },
            {
                path: "schedule", // Change this from "/messages" to "messages"
                element: <ClassSchedule></ClassSchedule>
            },
        ]
    }
]);

export default Router;
