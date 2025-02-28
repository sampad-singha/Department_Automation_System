import axios from "axios";

const axiosPublic = axios.create({
    baseURL:'http://127.0.0.1:8000'
})
const useAxiosPublic = () => {
    return axiosPublic;
};

export default useAxiosPublic;
