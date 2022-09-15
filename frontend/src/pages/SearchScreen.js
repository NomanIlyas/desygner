import React, {useEffect, useState, useRef} from 'react'
import {getImages} from "../api/image"
import {getUsers} from "../api/user"

const SearchScreen = () => {
    const LOADING = 0
    const IS_LOADING = 1
    const LOADED = 3
    const [images, setImages] = useState([])
    const [totalImages, setTotalImages] = useState(0)
    const [currentPage, setCurrentPage] = useState(1)
    const [users, setUsers] = useState([])
    const [loadingState, setLoadingState] = useState(LOADING)
    const screenLoaded = useRef(false)
    const [searchQuery, setSearchQuery] = useState("")
    const [selectedUser, setSelectedUser] = useState()
    const dropdown = useRef()

    useEffect(() => {
        
        if (!screenLoaded.current) {

            screenLoaded.current = true
            getUsers({pagination:false})
            .then(users => {
                setUsers(users)
            })
            fetchImages()
        }
    }, [])

    const toggleDropdown = () => {
        if (dropdown.current.style.display !== "block") {
            dropdown.current.style.display = "block"
        } else {
            dropdown.current.style.display = "none"
        }
    }

    const providerHanlder = (user) => {
        if (user) {
            setSelectedUser(user)
        } else {
            setSelectedUser(null)
        }
        toggleDropdown()
        setCurrentPage(1)
    }

    const fetchImages = () => {
        setLoadingState(IS_LOADING)
        const params = {pagination:true, page:currentPage}
        if (searchQuery) {
            params["tags.name"] = searchQuery
        }

        if (selectedUser && Object.keys(selectedUser).length) {
            params["userImages.user.fullName"] = selectedUser.displayName
        }

        getImages(params)
        .then(data => {
            setImages(data.images)
            setTotalImages(data.totalCount)
            setLoadingState(LOADED)
        })
    }

    const tagSeachHandler = (e) => {
        const queryString = e.target.value
        setSearchQuery(queryString)
        setCurrentPage(1)
    }

    useEffect(() => {
        if (screenLoaded.current) {
            fetchImages()
        }
    }, [searchQuery, selectedUser, currentPage])


    const previosPage = () => {
        if (currentPage > 1) {
            setCurrentPage(currentPage - 1)
        }
    }

    const nextPage = () => {
        if ((totalImages/30) - 1  > currentPage) {
            setCurrentPage(currentPage + 1)
        }
    }

    return <div className="search-container">
        <div className="search-bar">
            <div className="input-group">
                <input type="search" id="form1" className="form-control mx-2" onChange={tagSeachHandler} placeholder="Enter tag name"/>
            </div>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" onClick={toggleDropdown} type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {
                        selectedUser ? selectedUser.fullName : "All providers"
                    }
                </button>
                <div class="dropdown-menu dropdown-container cursor-pointer" ref={dropdown} aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" onClick={() => providerHanlder()}>All providers</a>
                    {
                        users.length && users.map(user => {
                            return <a class="dropdown-item" onClick={() => providerHanlder(user)}>{user.displayName}</a>
                        })
                    }
                </div>
            </div>
        </div>
        <div className="heading">
            {
                totalImages && currentPage ? <span className="page-no">Page: {currentPage}/{totalImages}</span> : <></>
            }
        </div>
        <div className="card-container">

            {
                loadingState === LOADED && images ? images.map((image, index) => {
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
                }) : (
                    loadingState === IS_LOADING ? <div className="spinner-container">
                                                    <div className="spinner-border" role="status">
                                                        <span className="sr-only"></span>
                                                    </div>
                                                </div> : <></>
                )
            }

            {
                loadingState === LOADED && totalImages > 1 &&
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        {
                            currentPage >= 1 &&
                            <li class="page-item cursor-pointer"><a class="page-link" onClick={() => previosPage()}>Previous</a></li>
                        }
                        {
                            totalImages/30 > currentPage &&
                            <li class="page-item cursor-pointer"><a class="page-link" onClick={() => nextPage()} >Next</a></li>
                        }
                    </ul>
                </nav>   
            }
        </div>
    </div>
}

export default SearchScreen;
