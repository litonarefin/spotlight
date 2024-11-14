import React from "react";

const Img = ({ src, alt }) => {
    return (
        <figure>
            <img width={24} height={24} src={src} alt={alt} />
        </figure>
    );
};

export default Img;
