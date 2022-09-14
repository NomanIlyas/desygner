import React, {useEffect, useState} from 'react'
import {getImages} from "../api/image"

const SearchScreen = () => {
    const [images, setImages] = useState([])

    useEffect(() => {
        getImages()
        .then(images => {
            setImages(images)
        })
    }, [])

    return <div className="search-container">
        <div className="search-bar">
            <div className="input-group">
                <input type="search" id="form1" className="form-control" />
                <button type="button" className="btn btn-primary">
                    Search
                </button>
            </div>
        </div>
        <div className="card-container">

            {
                images.map((image, index) => {
                    const imagePath = `${process.env.REACT_APP_API_SERVER}${image.source}`
                    return (
                        <div className="card">
                            <img src={imagePath} className="card-img-top" alt="..."/>
                            <div className="card-body">
                                <h5 className="card-title">{image.name}</h5>
                                {/* <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> */}
                                {/* <a href="#" className="btn btn-primary">Go somewhere</a> */}
                            </div>
                        </div>
                    )
                })
            }

        </div>
    </div>
}

export default SearchScreen;
