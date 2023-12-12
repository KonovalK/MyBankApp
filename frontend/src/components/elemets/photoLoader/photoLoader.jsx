import React from 'react';

const PhotoUpload = ({ onFileSelect }) => {
    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                const base64data = reader.result;
                onFileSelect({ file, base64: base64data });
            };
            reader.readAsDataURL(file);
        }
    };

    return (
        <div>
            <label htmlFor="photoInput">Choose a photo:</label>
            <input
                type="file"
                id="photoInput"
                accept="image/*"
                onChange={handleFileChange}
            />
        </div>
    );
};

export default PhotoUpload;
