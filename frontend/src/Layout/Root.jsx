import { Outlet } from "react-router-dom";
import Navbar from "../Component/Share/Navbar/Navbar";
import Footer from "../Component/Share/Footer/Footer";


const Root = () => {
    return (
        <div>
            <Navbar></Navbar>
            <Outlet></Outlet>
            <Footer></Footer>
        </div>
    );
};

export default Root;