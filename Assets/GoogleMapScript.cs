using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class GoogleMapScript : MonoBehaviour {

    string url = "";
    public float Lat = 33.776403f;
    public float Lon = -84.388744f;
    LocationInfo locInfo;
    public int zoomMap = 14;
    public int width =640;
    public int height =640;
    public enum googleMapTypes { roadmap, satellite, hybrid, terrain };
    public googleMapTypes mapType;
    public float scale;
    //private string API_key = "AIzaSyAfcqcitJKrlk4J5v4VmEtdBSQPiz8BpFw";
    private string API_key = "AIzaSyBqUTCQkWZMC2j8ojV42WGDI0vDKNeV_jA";

    private bool loadingMap = false;
    private IEnumerator mapCoroutine;

    public float moveInc = 0.01f;
    private float moveUnit;
    private float scrollWheel;
        

    IEnumerator GetGoogleMap (float lat, float lon)
    {
        url = "https://maps.googleapis.com/maps/api/staticmap?center=" + lat + "," + lon + "&zoom=" + zoomMap + "&size=" + width + "x" + height + "&scale=" + scale + "&maptype=" + mapType + "&key=" + API_key;
        //Debug.Log(url);
        loadingMap = true;
        WWW www = new WWW(url);
        yield return www;
        loadingMap = false;
        gameObject.GetComponent<RawImage>().texture = www.texture;
        StopCoroutine(mapCoroutine);
    } 
	// Use this for initialization
	void Start () {
        mapCoroutine = GetGoogleMap(Lat, Lon);
        StartCoroutine(mapCoroutine);
        moveUnit = moveInc * zoomMap;
	}
	
	// Update is called once per frame
	void Update () {
        
        if (Input.GetKeyDown(KeyCode.W))
        {
            Debug.Log("getting new Map");
            Lat = Lat +moveUnit;
            mapCoroutine = GetGoogleMap(Lat, Lon);
            StartCoroutine(mapCoroutine);
        }
        if (Input.GetKeyDown(KeyCode.S))
        {
            Debug.Log("getting new Map");
            Lat = Lat - moveUnit;
            mapCoroutine = GetGoogleMap(Lat, Lon);
            StartCoroutine(mapCoroutine);
        }
        if (Input.GetKeyDown(KeyCode.A))
        {
            Debug.Log("getting new Map");
            Lon = Lon + moveUnit;
            mapCoroutine = GetGoogleMap(Lat, Lon);
            StartCoroutine(mapCoroutine);
        }
        if (Input.GetKeyDown(KeyCode.D))
        {
            Debug.Log("getting new Map");
            Lon = Lon - moveUnit;
            mapCoroutine = GetGoogleMap(Lat, Lon);
            StartCoroutine(mapCoroutine);
        }

        scrollWheel = Input.GetAxis("Mouse ScrollWheel");    
        if (scrollWheel<0)
        {
            Debug.Log("zoom");
            zoomMap -= 1;
            mapCoroutine = GetGoogleMap(Lat, Lon);
            StartCoroutine(mapCoroutine);
        }

        if (scrollWheel > 0)
        {
            Debug.Log("zoom");
            zoomMap += 1;
            mapCoroutine = GetGoogleMap(Lat, Lon);
            StartCoroutine(mapCoroutine);
        }


    }
}
