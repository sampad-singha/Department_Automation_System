import React from 'react';

const ClassSchedule = () => {
    return (
        <div>
            <p className='text-3xl font-bold'> All class Schedule Here</p>
            <div className="w-[200px] h-[200px] bg-gray-200 overflow-y-auto border p-4">
                {/* Your content goes here */}
                <p>Your content goes here. This div will have a vertical scrollbar if the content exceeds the height.</p>
                <p>Add more content as needed to test the scrollbar.</p>
                <p>More content...</p>
                <p>More content...</p>
                <p>More content...</p>
                {/* Add more content to exceed the div height */}
            </div>
        </div>
    );
};

export default ClassSchedule;