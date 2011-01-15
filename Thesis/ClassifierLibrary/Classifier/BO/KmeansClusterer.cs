using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace cfm.Kmeans
{
    public class KmeansClusterer
    {
        // documents to be clustered
        private ArrayList documents;

        // k - num of clusters
        private int numOfClusters;

        // int = cluster id, object = array list of distance vectors
        private Dictionary<int, ArrayList> clusterCentriods;

        // int = cluster id, object = document
        private Dictionary<int, object> clusterMembership;



        public KmeansClusterer()
        { 
        }

        public void InitializeCentriods()
        {
        }
    }
}
