import { useEffect, useState } from 'react';
import axios from 'axios';
import { useParams } from 'react-router-dom';

const NoticeDetail = () => {
  const { id } = useParams();
  const [notice, setNotice] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);



  const baseUrl = 'http://127.0.0.1:8000';


  useEffect(() => {
    const fetchNoticeDetail = async () => {
      setLoading(true);
      setError(null);

      try {
        const response = await axios.get(`${baseUrl}/api/show-notice/${id}`);
        setNotice(response.data.notice); 
      } catch (err) {
        setError('Error fetching notice details');
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchNoticeDetail();
  }, [id]);



  if (loading) {
    return (
      <div className="text-xl text-center text-gray-600">
        <div className="w-10 h-10 mx-auto mb-4 border-4 border-t-4 border-blue-500 rounded-full animate-spin"></div>
        Loading...
      </div>
    );
  }

  if (error) {
    return (
      <div className="text-center text-red-500">
        <p>{error}</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen p-8 bg-gray-50">
      <div className="max-w-4xl p-6 mx-auto space-y-8 bg-white rounded-lg shadow-lg">
        <h1 className="mb-6 text-4xl font-semibold text-gray-800">Notice Details</h1>

    
        {notice && (
          <div className="space-y-6">
            <h2 className="text-2xl font-medium text-gray-800">{notice.notice.title}</h2>
            <p className="text-gray-600">{notice.notice.content}</p>

      
            <p className="text-sm text-gray-500">
              Published by: {notice.notice.published_by}
            </p>

           
            {notice.notice.archived_on ? (
              <p className="text-sm text-gray-500">
                Archived on: {new Date(notice.notice.archived_on).toLocaleDateString()}
              </p>
            ) : (
              <p className="text-sm text-gray-500">Archived on: Not available</p>
            )}

         
            {notice.notice.file && (
              <img
                src={`${baseUrl}/storage/${notice.notice.file}`}
                alt={notice.notice.title}
                className="w-full h-auto rounded-lg shadow-md"
              />
            )}

   
            {notice.notice.department && (
              <p className="text-sm text-gray-500">
                Department: {notice.notice.department.name}
              </p>
            )}

        
            <p className="text-sm text-gray-500">
              Published on: {new Date(notice.notice.created_at).toLocaleDateString()}
            </p>
          </div>
        )}
      </div>
    </div>
  );
};

export default NoticeDetail;
