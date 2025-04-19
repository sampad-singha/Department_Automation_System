import { useState, useEffect } from 'react';
import { CircularProgress } from '@mui/material';
import { toast } from 'react-toastify';
import api from '../api.jsx';

const ApplicationForm = ({ templateId, placeholders, onSubmitSuccess }) => {
    const [values, setValues] = useState({});
    const [file, setFile] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const handleChange = (e) => {
        setValues((prev) => ({ ...prev, [e.target.name]: e.target.value }));
    };

    useEffect(() => {
        if (error) {
            toast.error(error, {
                position: 'top-right',
                autoClose: 3000,
                hideProgressBar: true,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                theme: 'colored',
            });
            setError(null);
        }
    }, [error]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('application_template_id', templateId);
        formData.append('placeholders', JSON.stringify(values));
        if (file) {
            formData.append('attachment', file);
        }

        setLoading(true);
        try {
            await api.post('/applications/submit', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            onSubmitSuccess();
        } catch (err) {
            setError(err.response?.data?.message || 'Submission failed.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="bg-gray-900 p-6 rounded-lg border border-gray-700 w-full text-white">
            <h4 className="text-lg font-semibold mb-4">Fill in the application fields</h4>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {placeholders.map((ph) => (
                    <div key={ph} className="flex flex-col">
                        <label className="mb-1 capitalize text-gray-300">{ph.replace(/_/g, ' ')}:</label>
                        <input
                            type="text"
                            name={ph}
                            value={values[ph] || ''}
                            onChange={handleChange}
                            required
                            className="bg-gray-800 text-white border border-gray-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                ))}
                <div className="md:col-span-2 flex flex-col">
                    <label className="mb-1 text-gray-300">Attachment (optional):</label>
                    <input
                        type="file"
                        onChange={(e) => setFile(e.target.files[0])}
                        className="bg-gray-800 text-white border border-gray-600 rounded px-3 py-2 focus:outline-none"
                    />
                </div>
            </div>

            <button
                type="submit"
                disabled={loading}
                className="bg-blue-600 hover:bg-blue-500 px-5 py-2 rounded-md font-semibold text-white transition-all"
            >
                {loading ? <CircularProgress size={24} className="text-white" /> : 'Submit Application'}
            </button>
        </form>
    );
};

export default ApplicationForm;
