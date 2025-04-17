import { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { PaperClipIcon } from '@heroicons/react/20/solid';
import api from '../../../api';

export default function PublicationDetail() {
    const { id } = useParams();
    const [publication, setPublication] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchPublication = async () => {
            try {
                const response = await api.get(`/publications/${id}`);
                setPublication(response.data.publication);
            } catch (err) {
                setError('Failed to fetch publication details');
            } finally {
                setLoading(false);
            }
        };

        fetchPublication();
    }, [id]);

    if (loading) return <div className="text-center text-white">Loading...</div>;
    if (error) return <div className="text-center text-red-500">{error}</div>;

    if (!publication) return <div className="text-center text-white">Publication not found</div>;

    return (
        <div className="bg-gray-900 text-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
            <h1 className="text-3xl font-semibold mb-4">{publication.title}</h1>

            <div className="flex items-center space-x-2 mb-4">
                <span className="font-medium">DOI:</span>
                <p className="text-gray-400">
                    {publication.doi}
                </p>
            </div>

            <p className="mb-2">Journal: <span className="font-medium">{publication.journal}</span></p>
            <p className="mb-2">Volume: <span className="font-medium">{publication.volume}</span></p>
            <p className="mb-2">Pages: <span className="font-medium">{publication.pages}</span></p>
            <p className="mb-2">Published Date: <span className="font-medium">{publication.published_date}</span></p>

            <div className="my-4">
                <h2 className="text-xl font-semibold mb-2">Abstract</h2>
                <p>{publication.abstract}</p>
            </div>

            <div className="mt-4">
                {publication.url && (
                    <a
                        href={publication.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex items-center text-blue-400 hover:underline"
                    >
                        <PaperClipIcon className="h-5 w-5 mr-2" />
                        View Publication
                    </a>
                )}
            </div>
        </div>
    );
}
