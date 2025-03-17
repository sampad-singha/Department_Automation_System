import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import './App.css';
import Header from './Components/Header';
import Home from './pages/Home';
import About from './pages/About';
import Contact from './pages/Contact';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import ProtectedRoute from './layouts/ProtectedRoute';
import Notices from "./pages/Notices ";
import Result from "./pages/Result";
import NoticeDetail from "./pages/NoticeDetail ";

function App() {
  return (
    <Router>
      
      <Routes>
       
        <Route path="/login" element={<Login />} />

       
        <Route element={<ProtectedRoute />}>
          <Route
            path="/dashboard"
            element={
              <>
                <Header />
                <Dashboard />
              </>
            }
          />
        </Route>

       
        <Route
          path="/"
          element={
            <>
              <Header />
              <Home />
            </>
          }
        />
        <Route
          path="/about"
          element={
            <>
              <Header />
              <About />
            </>
          }
        />
        <Route
          path="/contact"
          element={
            <>
              <Header />
              <Contact />
            </>
          }
        />
        <Route
          path="/notice"
          element={
            <>
              <Header />
              <Notices />
            </>
          }
        >
          {/* Nested Route for Notice Detail */}
          <Route path=":id" element={<NoticeDetail />} />
        </Route>

        <Route
          path="/result"
          element={
            <>
              <Header />
              <Result />
            </>
          }
        />
      </Routes>
    </Router>
  );
}

export default App;
