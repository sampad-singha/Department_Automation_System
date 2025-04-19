import { useEffect, useState } from 'react';
import api from '../../../api.jsx'; // Ensure that api.jsx includes bearer token logic
import { CircularProgress } from '@mui/material';
import { toast } from 'react-toastify';

const ApplicationTeacher = () => {
    const [pendingApplications, setPendingApplications] = useState([]);
    const [pastApplications, setPastApplications] = useState([]);
    const [loading, setLoading] = useState(false); // General loading state
    const [error, setError] = useState(null); // Error state

    // Show errors using toast
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

    // Fetch pending applications and past applications on initial load
    useEffect(() => {
        fetchPendingApplications();
        fetchPastApplications();
    }, []);

    // Fetch pending applications
    const fetchPendingApplications = async () => {
        setLoading(true);
        try {
            const res = await api.get('/applications/pending');
            setPendingApplications(res.data.data);
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to load pending applications.');
        } finally {
            setLoading(false);
        }
    };

    // Fetch past applications
    const fetchPastApplications = async () => {
        setLoading(true);
        try {
            const res = await api.get('/applications/authorized');
            setPastApplications(res.data.data);
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to load past applications.');
        } finally {
            setLoading(false);
        }
    };

    // Approve or reject application
    const handleAuthorize = async (applicationId, action) => {
        setLoading(true);
        try {
            await api.post(`/applications/${applicationId}/authorize`, { action });
            const message = action === 'approve' ? 'Application approved and PDF generated.' : 'Application rejected.';
            toast.success(message);
            fetchPendingApplications(); // Reload pending applications
            fetchPastApplications(); // Reload past applications
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to process application.');
        } finally {
            setLoading(false);
        }
    };

    // Handle downloading approved application
    const handleDownload = async (applicationId) => {
        setLoading(true);
        try {
            const res = await api.get(`/applications/download/${applicationId}`);
            const link = document.createElement('a');
            link.href = res.data.url;
            link.download = `application_${applicationId}.pdf`;
            link.click();
        } catch (err) {
            setError(err.response?.data?.message || 'Failed to download application.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="p-6 min-h-screen bg-gray-900 text-white relative">
            {/* Loading Overlay */}
            {loading && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <CircularProgress size={60} thickness={4} />
                </div>
            )}

            <h2 className="text-2xl font-bold mb-6">Pending Applications</h2>

            <div className="overflow-x-auto mb-10">
                <table className="min-w-full bg-gray-800 shadow rounded-lg">
                    <thead>
                    <tr className="bg-gray-700 text-left text-sm font-semibold text-gray-300">
                        <th className="px-4 py-3">Title</th>
                        <th className="px-4 py-3">Applied By</th>
                        <th className="px-4 py-3">Applied At</th>
                        <th className="px-4 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {pendingApplications.length === 0 ? (
                        <tr>
                            <td colSpan={4} className="text-center px-4 py-6 text-gray-400">
                                No pending applications.
                            </td>
                        </tr>
                    ) : (
                        pendingApplications.map(app => (
                            <tr key={app.id} className="border-t border-gray-700 hover:bg-gray-700/50">
                                <td className="px-4 py-3">{app.application_template?.title || 'N/A'}</td>
                                <td className="px-4 py-3">{app.student?.name || 'N/A'}</td>
                                <td className="px-4 py-3">{new Date(app.created_at).toLocaleDateString()}</td>
                                <td className="px-4 py-3">
                                    <button
                                        onClick={() => handleAuthorize(app.id, 'approve')}
                                        className="px-4 py-1 text-sm bg-green-600 hover:bg-green-500 rounded text-white mr-2"
                                    >
                                        Approve
                                    </button>
                                    <button
                                        onClick={() => handleAuthorize(app.id, 'reject')}
                                        className="px-4 py-1 text-sm bg-red-600 hover:bg-red-500 rounded text-white"
                                    >
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        ))
                    )}
                    </tbody>
                </table>
            </div>

            <h2 className="text-2xl font-bold mb-6">Past Applications</h2>

            <div className="overflow-x-auto">
                <table className="min-w-full bg-gray-800 shadow rounded-lg">
                    <thead>
                    <tr className="bg-gray-700 text-left text-sm font-semibold text-gray-300">
                        <th className="px-4 py-3">Title</th>
                        <th className="px-4 py-3">Applied At</th>
                        <th className="px-4 py-3">Status</th>
                        <th className="px-4 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {pastApplications.length === 0 ? (
                        <tr>
                            <td colSpan={4} className="text-center px-4 py-6 text-gray-400">
                                No past applications.
                            </td>
                        </tr>
                    ) : (
                        pastApplications.map(app => (
                            <tr key={app.id} className="border-t border-gray-700 hover:bg-gray-700/50">
                                <td className="px-4 py-3">{app.application_template?.title || 'N/A'}</td>
                                <td className="px-4 py-3">{new Date(app.created_at).toLocaleDateString()}</td>
                                <td className="px-4 py-3 capitalize">{app.status}</td>
                                <td className="px-4 py-3">
                                    {app.status === 'approved' && (
                                        <button
                                            onClick={() => handleDownload(app.id)}
                                            className="text-blue-400 hover:underline cursor-pointer"
                                        >
                                            Download
                                        </button>
                                    )}
                                </td>
                            </tr>
                        ))
                    )}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default ApplicationTeacher;
